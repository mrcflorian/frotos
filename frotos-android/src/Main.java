import java.io.File;
import java.io.IOException;

import org.apache.commons.cli.BasicParser;
import org.apache.commons.cli.CommandLine;
import org.apache.commons.cli.Option;
import org.apache.commons.cli.Options;
import org.apache.commons.cli.ParseException;
import org.apache.commons.cli.Parser;
import org.apache.commons.io.FileUtils;
import org.apache.lucene.analysis.Analyzer;
import org.apache.lucene.analysis.en.EnglishAnalyzer;
import org.apache.lucene.store.Directory;
import org.apache.lucene.store.FSDirectory;
import org.apache.lucene.store.RAMDirectory;
import org.apache.lucene.util.Version;


public class Main {
	static CommandLine parseArguments(String[] arguments) {
		Options options = new Options();
		
		options.addOption(new Option("l", "lang", true, "language used"));
		options.addOption(new Option("d", "docs", true, "documents to index"));
		options.addOption(new Option("s", "search", true, "text to search for"));
		options.addOption(new Option("i", "index", true, "path to index if not in memory"));
		
		Parser parser = new BasicParser();
		try {
			return parser.parse(options, arguments);
		} catch (ParseException e) {
			String usage = "usage: project [-d docs] [-i index] [-s search] [-l lang [ro/eng]]";
			System.out.println(usage);
			
			return null;
		}
	}
	
	@SuppressWarnings("resource")
	public static void main(String[] args) {
		CommandLine pargs = parseArguments(args);
		if (pargs != null) {
			
			Directory indexDirectory;
			try {
				String indexDirectoryPath = pargs.getOptionValue("index");
				if (indexDirectoryPath != null) {
					File file = new File(indexDirectoryPath);
					if (file.exists()) {
						FileUtils.deleteDirectory(file);
					}
				}
				
				indexDirectory = (pargs.getOptionValue("index") != null) ? 
						FSDirectory.open(new File(pargs.getOptionValue("index"))) :
						new RAMDirectory();
			} catch (IOException e1) {
				System.out.println("Could not create/open the index directory");
				return;
			}
			
			Analyzer analyzer;
			if (!pargs.hasOption("lang") || pargs.getOptionValue("lang").equals("en")) {
				analyzer = new EnglishAnalyzer(Version.LUCENE_42);
			} else if (pargs.getOptionValue("lang").equals("ro")){
				analyzer = new ModifiedRomanianAnalyzer(Version.LUCENE_42);
			} else {
				System.out.println("Unsupported language at this moment. Use ro or en instead.");
				return;
			}
			
			if (pargs.hasOption("docs")) {
				Indexer indexer = new Indexer(indexDirectory, analyzer);
				
				try {
					File docs = new File(pargs.getOptionValue("docs"));
					indexer.index(docs);
				} catch (IOException e) {
					System.out.println("Could not index documents!");
					return;
				}
			}
			
			if (pargs.hasOption("search")) {
				Searcher searcher = new Searcher(indexDirectory, analyzer);
				
				try {
					File text = new File(pargs.getOptionValue("search"));
					searcher.search(text);
				} catch (Exception e) {
					System.out.println(e.toString());
					System.out.println("Could not search for the given text");
					return;
				}
			}
		}
	}
}
