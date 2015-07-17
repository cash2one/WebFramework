#!/usr/bin/python

def build_test_data(raw_data_file, output_dir):
    dataDict = {
        "EADS": [],
        "EADM": [], 
        "EADC": [], 
        "OFFLINE": [], 
        "EADD": [], 
        "DSP": [], 
        "EADU": [],
    }

    for line in open(raw_data_file): 
        line = line.strip()
        if "Invalid" in line: continue

        syndId, clickUrl = line.split(":", 1)
        if syndId == "-1":
            continue

        try:
            syndId = int(syndId)
        except:
            print "invalid: ", syndId
            continue

        if syndId in range(5, 11):
            dataDict["EADM"].append(clickUrl)

        elif syndId in range(10, 16) + range(17, 21):
            dataDict["EADC"].append(clickUrl)
            
        elif syndId == 16:
            dataDict["OFFLINE"].append(clickUrl)

        elif syndId in range(50, 101):
            dataDict["EADD"].append(clickUrl)

        elif syndId in range(101, 151):
            dataDict["DSP"].append(clickUrl)

        elif syndId >= 1000:
            dataDict["EADU"].append(clickUrl)

        else:
            dataDict["EADS"].append(clickUrl)

    for prod, urlList in dataDict.items():
        open("%s/%s" % (output_dir, prod), "w").write("\n".join(urlList[:100]))


if __name__ == "__main__":
    build_test_data("raw_data", "../testdata")
