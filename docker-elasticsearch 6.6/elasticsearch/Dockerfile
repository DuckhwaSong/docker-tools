
# https://www.docker.elastic.co/
#FROM docker.elastic.co/elasticsearch/elasticsearch:${ELK_VERSION}
FROM elasticsearch:6.6.0

# Add your elasticsearch plugins setup here
# Example: RUN elasticsearch-plugin install analysis-icu
#RUN elasticsearch-plugin install analysis-nori
RUN /usr/share/elasticsearch/bin/elasticsearch-plugin install https://github.com/netcrazy/elasticsearch-jaso-analyzer/releases/download/v6.6.0/jaso-analyzer-plugin-6.6.0.0-plugin.zip
RUN /usr/share/elasticsearch/bin/elasticsearch-plugin install analysis-nori


# jaso 세팅 - fm_goods/_doc
RUN curl -XDELETE 'http://localhost:9200/fm_goods'
RUN curl -XPUT -H 'Content-Type: application/json' http://localhost:9200/fm_goods/ -d '{
  "settings": {
    "index": {
      "analysis": {
        "filter": {
          "suggest_filter": {
            "type": "ngram",
            "min_gram": 2,
            "max_gram": 50
          }
        },
        "tokenizer": {
          "jaso_search_tokenizer": {
            "type": "jaso_tokenizer",
            "mistype": true,
            "chosung": false
          },
          "jaso_index_tokenizer": {
            "type": "jaso_tokenizer",
            "mistype": true,
            "chosung": true
          }
        },
        "analyzer": {
          "suggest_search_analyzer": {
            "type": "custom",
            "tokenizer": "jaso_search_tokenizer"
          },
          "suggest_index_analyzer": {
            "type": "custom",
            "tokenizer": "jaso_index_tokenizer",
            "filter": [
              "suggest_filter"
            ]
          }
        }
      }
    }
  },
  "mappings": {
    "_doc": {
      "properties": {
        "goods_search": {
          "type": "text",
          "store": true,
          "analyzer": "suggest_index_analyzer",
          "search_analyzer": "suggest_search_analyzer"
          },
          "goods_search_reverse": {
          "type": "text",
          "store": true,
          "analyzer": "suggest_index_analyzer",
          "search_analyzer": "suggest_search_analyzer"
          }
      }
    }
  }
}'