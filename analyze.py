from textblob import TextBlob
import sys

reload(sys)
sys.setdefaultencoding("utf-8")
f = open ("corpus_smartwatches.txt", "r")
text = f.read()

blob = TextBlob(text)
threegram = blob.ngrams(n=3)

for gram in threegram:
    s = "-"
    for g in gram:
        s = s + g + "-"
    print s
