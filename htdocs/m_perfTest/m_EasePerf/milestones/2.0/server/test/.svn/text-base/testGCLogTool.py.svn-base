import os
import gcLogProcessor

logLines=['58110.449: [GC 58110.449: [ParNew: 409740K->38K(460800K), 0.0065020 secs] 888316K->478614K(1996800K) icms_dc=0 , 0.0065760 secs] [Times: user=0.03 sys=0.00, real=0.01 secs] ', '58130.565: [GC 58130.565: [ParNew: 409638K->20K(460800K), 0.0041550 secs] 888214K->478596K(1996800K) icms_dc=0 , 0.0042180 secs] [Times: user=0.01 sys=0.00, real=0.01 secs] ', '58154.299: [GC 58154.299: [ParNew: 409620K->14K(460800K), 0.0024460 secs] 888196K->478590K(1996800K) icms_dc=0 , 0.0025170 secs] [Times: user=0.00 sys=0.00, real=0.01 secs] ']

pro=gcLogProcessor.GCLogProcessor()
print pro.getStatisticData(logLines)
