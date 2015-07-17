import base64  
import hashlib  
import time  
import sys

def encrypt(strs,isEncrypt=0,key='fff'):  
        now_time = time.time()  
        dynKey   = hashlib.new("sha1",str(now_time)).hexdigest() if isEncrypt == 1 else strs[0:40]      
        dykey1= dynKey[0:20]  
        dykey2= dynKey[20:]  
        fixKey = hashlib.new("sha1",key).hexdigest()  
        fixkey1 = fixKey[0:20]  
        fixkey2 = fixKey[20:]  
        newkey = hashlib.new("sha1",dykey1+fixkey1+dykey2+fixkey2).hexdigest()
        
        if(isEncrypt == 1):  
            newstring = fixkey1 + strs + dykey2   
        else:  
            newstring = base64.b64decode(strs[40:].replace('_', '='))

        re=''      
        strlen= len(newstring)  
        for i in range(0,strlen):  
            j=i%40  
            re +=chr(ord(newstring[i])^ord(newkey[j]))  
        return dynKey + base64.b64encode(re).replace('=','_') if isEncrypt == 1 else re[20:-20]  
if __name__ == "__main__":
        var = "953019aeba8dfd3920c5619537e2c11b2758f472BA9aBApVDloEUQYBXAVUUFMGXA9cAVhbXUdXF15VAgZfVgQABlQCVVNbUQcGDFANBAc_"
        print encrypt(sys.argv[1])

