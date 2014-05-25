import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStreamReader;

import org.apache.lucene.analysis.Analyzer;
import org.apache.lucene.document.Document;
import org.apache.lucene.index.DirectoryReader;
import org.apache.lucene.index.IndexReader;
import org.apache.lucene.queryparser.classic.MultiFieldQueryParser;
import org.apache.lucene.queryparser.classic.ParseException;
import org.apache.lucene.queryparser.classic.QueryParser;
import org.apache.lucene.search.IndexSearcher;
import org.apache.lucene.search.Query;
import org.apache.lucene.search.ScoreDoc;
import org.apache.lucene.search.TopScoreDocCollector;
import org.apache.lucene.store.Directory;
import org.apache.lucene.store.FSDirectory;
import org.apache.lucene.util.Version;


public class Searcher {
	private final int HITS_PER_PAGE = 10;
	
	private File indexFile = null;
	private Directory indexDirectory = null;
	private Analyzer analyzer;
	
	public Searcher(File indexFile, Analyzer analyzer) {
		this.indexFile = indexFile;
		this.analyzer = analyzer;
	}
	
	public Searcher(Directory indexDirectory, Analyzer analyzer) {
		this.indexDirectory = indexDirectory;
		this.analyzer = analyzer;
	}
	
	public void search(File text) throws IOException, ParseException {
		BufferedReader br = new BufferedReader(new InputStreamReader(new FileInputStream(text), "UTF-8"));
		
		/* QueryParser queryParser = new QueryParser(Version.LUCENE_42, "content", analyzer); */
		
		/* Map<String, Float> boosts = new HashMap<String, Float>();
		boosts.put("header", 2.0f);
		boosts.put("footer", 1.0f);
		
		QueryParser queryParser = new MultiFieldQueryParser(Version.LUCENE_42, new String[] {"header", "footer"}, analyzer, boosts); */
		
		QueryParser queryParser = new MultiFieldQueryParser(Version.LUCENE_42, new String[] {"header", "footer"}, analyzer);
		
		Directory directory = (indexDirectory != null) ? indexDirectory : FSDirectory.open(indexFile);
		IndexReader indexReader = DirectoryReader.open(directory);
		IndexSearcher indexSearcher = new IndexSearcher(indexReader);
		
		String line = br.readLine();
		while (line != null) {			
			Query query = queryParser.parse(line);
			TopScoreDocCollector collector = TopScoreDocCollector.create(HITS_PER_PAGE, true);
			indexSearcher.search(query, collector);
			
			ScoreDoc[] hits = collector.topDocs().scoreDocs;
			
			System.out.println("---------------------------------------------");
			System.out.println(hits.length + " hits found for query: \"" + line + "\":\n");
			for (int i = 0; i < hits.length; ++i) {
				Document document = indexSearcher.doc(hits[i].doc);
				System.out.println("\tfile: " + document.get("name") + "\t score: " + hits[i].score);
			}
			System.out.println();
			
			line = br.readLine();
		}
		
		br.close();
	}
}
