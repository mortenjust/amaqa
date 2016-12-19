from textblob import TextBlob
import sys

reload(sys)
sys.setdefaultencoding("utf-8")
f = open ("corpus_smartwatch.txt", "r")
text = f.read()

blob = TextBlob(text)
nouns = blob.np_counts


for noun in nouns:
    
    print noun
    
    # for n in noun:
    #     s = s + n + "-"
    # print s
