from textblob import TextBlob
import sys

reload(sys)
sys.setdefaultencoding("utf-8")
f = open ("corpus_smartwatch.txt", "r")
text = f.read()

blob = TextBlob(text)
results = blob.tags

for result in results:
    print result[0]+","+result[1]

# for noun in nouns:    
#     print noun
    
    # for n in noun:
    #     s = s + n + "-"
    # print s
