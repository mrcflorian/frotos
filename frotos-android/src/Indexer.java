import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;

import org.apache.commons.lang3.StringUtils;
import org.apache.lucene.analysis.Analyzer;
import org.apache.lucene.document.Document;
import org.apache.lucene.document.Field;
import org.apache.lucene.document.StringField;
import org.apache.lucene.document.TextField;
import org.apache.lucene.index.IndexWriter;
import org.apache.lucene.index.IndexWriterConfig;
import org.apache.lucene.store.Directory;
import org.apache.lucene.util.Version;
import org.apache.tika.Tika;
import org.apache.tika.exception.TikaException;
import org.apache.tika.metadata.Metadata;


public class Indexer {
	private static int LIMIT = 10;
	
	private Directory indexDirectory;
	private Analyzer analyzer;
	
	public Indexer(Directory indexDirectory, Analyzer analyzer) {
		this.indexDirectory = indexDirectory;
		this.analyzer = analyzer;
	}
	
	private void indexDocuments(IndexWriter writer, File docs) throws IOException {
		if (docs.isFile()) {
			Document doc = new Document();
			
			Field path = new StringField("name", docs.getName(), Field.Store.YES);
			doc.add(path);
			
			InputStream is = new FileInputStream(docs);
			
			Metadata metadata = new Metadata();
			metadata.set(Metadata.RESOURCE_NAME_KEY, docs.getCanonicalPath());
			
			Tika tika = new Tika();
			try {
				String[] words = tika.parseToString(is, metadata).trim().split("[\\s\\xA0]+");
				
				String header = StringUtils.join(words, " ", 0, Math.min(LIMIT, words.length));
				TextField headerField = new TextField("header", header, Field.Store.YES);
				headerField.setBoost(2.0f);
				doc.add(headerField);
				
				if (LIMIT < words.length) {
					String footer = StringUtils.join(words, " ", LIMIT, words.length);
					TextField footerField = new TextField("footer", footer, Field.Store.YES);
					footerField.setBoost(1.0f);
					doc.add(footerField);
				}
				
			} catch (TikaException e) {
				e.printStackTrace();
			}
			
			writer.addDocument(doc);
		} else {
			File[] files = docs.listFiles();
			if (files != null) {
				for (int i = 0; i < files.length; ++i) {
					indexDocuments(writer, files[i]);
				}
			}
		}
	}
	
	public void index(File docs) throws IOException {
		
		IndexWriterConfig config = new IndexWriterConfig(Version.LUCENE_42, analyzer);
		
		IndexWriter writer = new IndexWriter(indexDirectory, config);
		indexDocuments(writer, docs);
		writer.close();
	}
}
