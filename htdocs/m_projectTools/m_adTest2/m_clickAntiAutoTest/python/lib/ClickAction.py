#!/usr/bin/python
#encoding: utf-8

from Utility import *
from Log import *

class ClickAction:

    def __init__(self, inputFile):
        self.inputFile = inputFile

    def _setReqParam(self, key, value):
        Log.write("set clickAction(%s = %s)" % (key, value))
        Utility.appendToFile(self.inputFile, "%s=%s" % (key, value))
        return self

    def reset(self):
        Log.write("clear input file:%s" % self.inputFile)
        Utility.emptyFile(self.inputFile)
        return self

    def setReferer(self, referer):
        return self._setReqParam("Referer", referer)

    def setClickId(self, clickId):
        return self._setReqParam("ClickerId", clickId)

    def setClickerIp(self, clickerIp):
        return self._setReqParam("ClickerIp", clickerIp)

    def setClickTime(self, clickTime):
        return self._setReqParam("ClickTime", clickTime)

    def setCommitTime(self, commitTime):
        return self._setReqParam("CommitTime", commitTime)

    def setSignature(self, signature):
        return self._setReqParam("Signature", signature)

    def setMemberId(self, memberId):
        return self._setReqParam("MemberId", memberId)

    def setSponsorId(self, sponsorId):
        return self._setReqParam("SponsorId", sponsorId)

    def setCampaignId(self, campaignId):
        return self._setReqParam("CampaignId", campaignId)

    def setAdGroupId(self, adGroupId):
        return self._setReqParam("AdGroupId", adGroupId)

    def setSuperKeyword(self, superKeyword):
        return self._setReqParam("SuperKeyword", superKeyword)

    def setAdVariationId(self, adVariationId):
        return self._setReqParam("AdVariationId", adVariationId)

    def setKeywordId(self, keywordId):
        return self._setReqParam("KeywordId", keywordId)

    def setKeyword(self, keyword):
        return self._setReqParam("Keyword", keyword)

    def setSyndicationId(self, syndicationId):
        return self._setReqParam("SyndicationId", syndicationId)

    def setSiteId(self, siteId):
        return self._setReqParam("SiteId", siteId)

    def setCodeId(self, codeId):
        return self._setReqParam("CodeId", codeId)

    def setImprPos(self, imprPos):
        return self._setReqParam("ImprPos", imprPos)

    def setQualityScore(self, qualityScore):
        return self._setReqParam("QualityScore", qualityScore)

    def setRank(self, rank):
        return self._setReqParam("Rank", rank)

    def setMaxCpc(self, maxCpc):
        return self._setReqParam("MaxCpc", maxCpc)

    def setImprIp(self, imprIp):
        return self._setReqParam("ImprIp", imprIp)

    def setImprRequest(self, imprRequest):
        return self._setReqParam("ImprRequest", imprRequest)

    def setOrigCost(self, origCost):
        return self._setReqParam("OrigCost", origCost)

    def setComments(self, comments):
        return self._setReqParam("Comments", comments)

    def setNominatorId(self, nominatorId):
        return self._setReqParam("NominatorId", nominatorId)

    def setActuCost(self, actuCost):
        return self._setReqParam("ActuCost", actuCost)

    def setAdType(self, adType):
        return self._setReqParam("AdType", adType)

    def setImprTime(self, imprTime):
        return self._setReqParam("ImprTime", imprTime)
