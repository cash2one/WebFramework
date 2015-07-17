$(function() {
    var id_url_map = {
        "101": "audit/viewAH.do?isAgent=false",
        "102": "audit/viewRate.do?isAgent=false",
        "103": "qas/viewQueryAgentSponsor.do?isAgent=false",
        "104": "adpub/viewAsList.do?isAgent=false",
        "105": "qas/revenueSummaryController.do",

        "201": "audit/viewAH.do?isAgent=true",
        "202": "audit/viewRate.do?isAgent=true",
        "203": "qas/viewQueryAgentSponsor.do?isAgent=true",
        "204": "adpub/viewAsList.do?isAgent=true",
        "205": "adpub/agentDiscount.do?isAgent=true",

        "301": "audit/viewMonthlySponsorFlag.do",
        "302": "audit/viewMonthlySponsorAH.do?ftPage=1",
        "303": "audit/viewMonthlySponsorRate.do?st=1",
        "304": "audit/viewUnauditMonthlySponsorSettlement.do",
        "305": "qas/viewMonthlySponsor.do?isAgent=false&isAudit=true",
        "306": "qas/viewMonthlySponsor.do?isAgent=false",
        "307": "qas/viewMonthlySponsorSettlement.do?st=1",
        "308": "adpub/viewMonthlySponsorList.do",
        "309": "qas/viewMonthlySponsorQAS.do?ftPage=1",

        "401": "adpub/viewUnflaggedMonthlySponsorList.do",
        "402": "qas/viewBatchFileList.do",
        "403": "qas/viewInvoice.do",
        "404": "qas/viewCashFlow.do?isAgent=false",
        "405": "qas/extraRevenueController.do",
    };

    $("ul#menu li ul li a").click(function(e) {
        var id = $(this).attr('id');
        var url = id_url_map[id]
        window.open("http://a.corp.youdao.com/fin/" + url, "new");
        e.preventDefault();
    });
});
