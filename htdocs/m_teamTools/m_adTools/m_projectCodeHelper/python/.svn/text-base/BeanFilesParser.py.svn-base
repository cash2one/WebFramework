#!/usr/bin/python
#encoding:utf-8

from Utility import *
from NodeType import *
import json

# global settings for xml files' locations
xml_conf_dict = {
    "finance": [
        "war/WEB-INF/applicationContext.xml",
        "war/WEB-INF/dataAccessContext-jta.xml",
        "war/WEB-INF/eadfin-servlet.xml",
        "war/WEB-INF/applicationContext-bizcommons.xml",
        "war/WEB-INF/applicationContext-bizcommons-db.xml",
        "war/WEB-INF/applicationContext-security-urs.xml",
        "war/WEB-INF/dataAccessContext-security-urs.xml",
        "war/WEB-INF/statlog.base.xml",
    ],
    "antifrauder" : [
        "conf/modules/antifrauder/context/antifrauder-filterlist.xml",
        "conf/modules/antifrauder/context/antifrauder.xml",
    ],
    "mail" : [
        "conf/modules/eadm/context/mail-rrf.xml",
        "conf/beans/union-site-dao.xml.template",
        "conf/beans/data-cache-client-v2.xml.template",
        "src/java/outfox/ead/adnet/elector/filter/AllElectorFilterContext.xml",
        "src/java/outfox/ead/adnet/products/CommonContext.xml",
        "src/java/outfox/ead/adnet/products/eadm/EadmCommonContext.xml",
        "src/java/outfox/ead/adnet/products/eadm/EadmNominatorContext.xml",
        "src/java/outfox/ead/adnet/products/eadm/EadmElectorContext.xml",
        "src/java/outfox/ead/adnet/products/eadm/EadmHandlerContext.xml",
        "src/java/outfox/ead/adnet/match/content/toolbox/TermCtrDataProviderContext.xml",
        "src/java/outfox/ead/db/service/DataCacheClientContext.xml",
        "conf/beans/data-cache-utility-beans.xml.template",
        "src/java/outfox/ead/adnet/renderer/style/StyleTemplateManagerContext.xml",
    ],
    "channel" : [
        "conf/modules/eadu/context/eadu-channel-right.xml",
        "conf/beans/data-cache-client-v2.xml.template",
        "src/java/outfox/ead/impr/qs/BlackListedCustomerContext.xml",
        "src/java/outfox/ead/adnet/products/CommonContext.xml",
        "src/java/outfox/ead/adnet/products/eadu/EaduCommonContext.xml",
        "src/java/outfox/ead/adnet/products/eadu/EadcRightNominatorContext.xml",
        "src/java/outfox/ead/adnet/products/eadu/EaduImprCtrlContext.xml",
        "src/java/outfox/ead/adnet/match/lr/LrContext.xml",
        "conf/beans/union-site-dao.xml.template",
        "src/java/outfox/ead/adnet/renderer/style/StyleTemplateManagerContext.xml",
        "src/java/outfox/ead/adnet/products/eadimage/ImageContext.xml.template",
        "src/java/outfox/ead/adnet/products/eadimage/ImageExpContext.xml",
        "src/java/outfox/ead/adnet/products/eadimage/EcpmSelectStrategyContext.xml",
        "src/java/outfox/ead/antifrauder/MouseTraceConfigContext.xml",
        "conf/beans/data-cache-client.xml.template",
        "conf/beans/data-cache-utility-beans.xml.template",
        "src/java/outfox/ead/adnet/products/eadu/EaduBrandingNominatorContext.xml",
    ],
    "dict" : [
        "conf/modules/eadd/context/dict-inforbar-imprd.xml",
        "conf/beans/union-site-dao.xml.template",
        "src/java/outfox/ead/impr/qs/BlackListedCustomerContext.xml",
        "src/java/outfox/ead/adnet/products/eadd/DictContext.xml",
        "src/java/outfox/ead/adnet/products/CommonContext.xml", 
        "conf/modules/eadd/context/dict-inforbar-onebox.xml.template",
        "conf/modules/eadd/context/dict-rrf-img.xml",
        "src/java/outfox/ead/adnet/products/eadimage/ImageContext.xml",
        "src/java/outfox/ead/adnet/products/eadd/branding/DictBranding.xml",
        "src/java/outfox/ead/adnet/renderer/style/StyleTemplateManagerContext.xml",
        "conf/modules/eadd/context/dict-rrf-result.xml",
        "conf/beans/data-cache-client.xml.template",
        "conf/beans/data-cache-utility-beans.xml.template",
        "src/java/outfox/ead/adnet/products/eadimage/EcpmSelectStrategyContext.xml",
        "src/java/outfox/ead/antifrauder/MouseTraceConfigContext.xml",
        "conf/beans/advdb-dao-single.xml.template",
    ],
    "union" : [
        "conf/modules/eadu/context/eadu.xml",
        "conf/beans/union-site-dao.xml.template",
        "conf/beans/img-direct-dao.xml.template",
        "conf/beans/siteDirectText.xml.template",
        "conf/beans/advdb-dao-single.xml.template",
        "conf/beans/data-cache-client-v2.xml.template",
        "src/java/outfox/ead/adnet/renderer/style/StyleTemplateManagerContext.xml",
        "src/java/outfox/ead/adnet/elector/observer/AllElectorObserverContext.xml",
        "src/java/outfox/ead/adnet/elector/filter/AllElectorFilterContext.xml",
        "src/java/outfox/ead/adnet/products/CommonContext.xml",
        "src/java/outfox/ead/adnet/products/eadu/EaduContext.xml",
        "src/java/outfox/ead/adnet/products/eadu/EaduNominatorContext.xml",
        "src/java/outfox/ead/adnet/products/eadu/EaduElectorContext.xml",
        "src/java/outfox/ead/adnet/products/eadu/EaduLinkWordContext.xml",
        "src/java/outfox/ead/adnet/dsp/DspNominatorContext.xml.template",
        "src/java/outfox/ead/adnet/match/lrmix/Context.xml",
    ],
    "search" : [
        "conf/modules/eads/context/eads-imprd.xml.template",
        "conf/beans/data-cache-client-v2.xml.template",
        "src/java/outfox/ead/adnet/elector/filter/AllElectorFilterContext.xml",
        "conf/modules/eads/context/eads-monitor.xml.template",
        "conf/modules/eads/context/eads-query-analysis.xml.template",
        "conf/modules/eads/context/eads-adssearch-controller.xml.template",
        "conf/modules/eads/context/eads-cooperation-ad.xml.template",
        "conf/modules/eads/context/eads-ad-filters.xml.template",
        "conf/modules/eads/context/eads-ctr-predictor.xml.template",
        "conf/modules/eads/context/eads-rankscore-calculator.xml.template",
    ],
    "omedia" : [
        "conf/modules/eadu/context/eadu-omedia.xml", 
        "conf/beans/union-site-dao.xml.template",
        "conf/beans/data-cache-client-v2.xml.template",
        "src/java/outfox/ead/adnet/renderer/style/StyleTemplateManagerContext.xml",
        "src/java/outfox/ead/impr/qs/BlackListedCustomerContext.xml",
        "src/java/outfox/ead/adnet/products/CommonContext.xml",
        "src/java/outfox/ead/adnet/products/eadu/EaduCommonContext.xml",
        "src/java/outfox/ead/adnet/products/eadu/EaduBrandingContext.xml",
        "src/java/outfox/ead/adnet/products/eadu/EaduBrandingNominatorContext.xml",
        "src/java/outfox/ead/adnet/products/eadu/EaduDirectContext.xml",
        "src/java/outfox/ead/adnet/products/eadu/EaduDirectBrandingContext.xml",
        "src/java/outfox/ead/adnet/products/eadimage/ImageContext.xml.template", 
        "src/java/outfox/ead/adnet/products/eadu/EaduImprCtrlBrandingContext.xml",
        "src/java/outfox/ead/adnet/products/eadu/EaduImprCtrlContext.xml",
        "src/java/outfox/ead/adnet/products/eadu/EaduOmediaContext.xml",
        "src/java/outfox/ead/adnet/products/eadu/EaduUniversalAdMonitorContext.xml",
        "src/java/outfox/ead/adnet/products/eadu/EaduStaticClasses.xml",
        "src/java/outfox/ead/adnet/products/brand/framework/impl/BrandContext.xml",
        "conf/beans/adpublish-db-dao.xml.template",
        "src/java/outfox/ead/adnet/products/brand/framework/impl/BrandAdSource.xml",
        "src/java/outfox/ead/antifrauder/MouseTraceConfigContext.xml",
        "src/java/outfox/ead/adnet/products/eadimage/ImageExpContext.xml",
        "src/java/outfox/ead/adnet/products/eadimage/EcpmSelectStrategyContext.xml",
        "src/java/outfox/ead/adnet/elector/observer/AllElectorObserverContext.xml",
        "conf/beans/siteDirectText.xml.template",
        "conf/beans/img-direct-dao.xml.template",
        "conf/beans/advdb-dao-single.xml.template",
        "conf/beans/data-cache-client.xml.template",
        "conf/beans/data-cache-utility-beans.xml.template",
    ],
    "adpublish" : [
        "web/WEB-INF/applicationContext.xml", 
        "web/WEB-INF/adpublish.url-mapping.xml",
        "web/WEB-INF/adpublish.db.base.xml.template",
        "web/WEB-INF/statlog.base.xml.template",
        "web/WEB-INF/adpublish.base.xml.template",
        "web/WEB-INF/finance.base.xml.template",
        "web/WEB-INF/tools.base.xml.template",
        "web/WEB-INF/applicationContext-domain.xml.template",
        "web/WEB-INF/competePrice.db.base.xml.template",
        "web/WEB-INF/competePrice.base.xml.template",
        "web/WEB-INF/front.db.base.xml.template",
        "web/WEB-INF/front.base.xml.template",
        "web/WEB-INF/deliveryInfo.db.base.xml.template",
        "web/WEB-INF/quality.xml.template",
        "web/WEB-INF/certificateAudit.db.base.xml.template",
        "web/WEB-INF/certificateAudit.base.xml.template",
        "web/WEB-INF/webservice-client.xml.template",
        "web/WEB-INF/account-optimize.base.xml.template",
        # "src/outfox/ead/monitor/performance/monitor.base.xml",
    ], 
    "adpublish_service" : [
        "web/WEB-INF/adpublish.db.base.xml.template",
        "web/WEB-INF/statlog.base.xml.template",
        "web/WEB-INF/finance.base.xml.template",
        "web/WEB-INF/applicationContext-domain.xml",
        "web/WEB-INF/deliveryInfo.db.base.xml.template",
        "web/WEB-INF/data-migration.db.base.xml.template",
        "web/WEB-INF/lcxf.xml",
        "web/WEB-INF/account-optimize.base.xml.template",
        "web/WEB-INF/smart-search.base.xml.template",
    ],
    "audit2": [
        "web/WEB-INF/dataAccessContext-jta.xml.template",
        "web/WEB-INF/applicationContext.xml.template",
        "web/WEB-INF/applicationContext-master-job.xml.template",
        "web/WEB-INF/applicationContext-security-urs.xml.template",
        "web/WEB-INF/dataAccessContext-security-urs.xml.template",
    ],
    "adagent": [
        "web/WEB-INF/applicationContext.xml",
        "web/WEB-INF/applicationContext-security-urs.xml.template",
        "web/WEB-INF/dataAccessContext-security.xml.template",
        "web/WEB-INF/adpublish.db.base.xml.template",
        "web/WEB-INF/statlog.base.xml.template",
        "web/WEB-INF/adpublish.base.xml.template",
        "web/WEB-INF/finance.base.xml.template",
        "web/WEB-INF/tools.base.xml.template",
        "web/WEB-INF/adpublish-agent.base.xml.template",
        "web/WEB-INF/competePrice.db.base.xml.template",
        "web/WEB-INF/competePrice.base.xml.template",
        "web/WEB-INF/dataAccessContext-jta.xml.template",
        "web/WEB-INF/applicationContext-jta.xml.template",
        "web/WEB-INF/quality.xml.template",
        "web/WEB-INF/adpublish.url-mapping.xml",
    ],
    "account": [
        "conf/modules/accnt/context/accnt.xml",
        "conf/modules/accnt/context/ead-biz-commons.xml",
        "conf/modules/accnt/context/dataserv-client.xml",
        "src/java/outfox/ead/accnt/AccntMngr.xml",
        "src/java/outfox/ead/accnt/AccntDataSource.xml",
        "src/java/outfox/ead/accnt/BudgetManager.xml",
        "src/java/outfox/ead/accnt/misc/ClickManager.xml",
        "src/java/outfox/ead/accnt/misc/AdStatusOperator.xml",
        "src/java/outfox/ead/accnt/rule/Rule.xml",
        "src/java/outfox/ead/accnt/source/Source.xml",
        "src/java/outfox/ead/accnt/budget/VirtualBudgetBizManager.xml",
    ],
    "dsp": [
        "conf/modules/dsp/bid-server.xml",
        "conf/modules/dsp/sync-budget.xml",
        "conf/beans/data-cache-client-v2.xml.template",
        "src/java/outfox/ead/adnet/products/CommonContext.xml",
        "src/java/outfox/ead/adnet/renderer/style/StyleTemplateManagerContext.xml",
        "src/java/outfox/ead/adnet/products/retarget/RetargetContext.xml",
        "src/java/outfox/ead/adnet/products/dsp/DspContext.xml",
        "src/java/outfox/ead/adnet/products/eadu/EaduElectorContext.xml",
        "src/java/outfox/ead/adnet/elector/observer/AllElectorObserverContext.xml",
        "conf/beans/siteDirectText.xml.template",
        "conf/beans/img-direct-dao.xml.template",
        "src/java/outfox/ead/adnet/products/eadu/EaduContext.xml",
    ], 
    "dsp_publish": [
        "web/WEB-INF/dsppublish.db.xml.template",
        "web/WEB-INF/dsppublish.url-mapping.xml",
        "web/WEB-INF/dsppublish.base.xml.template",
        "web/WEB-INF/finance.base.xml.template",
        "web/WEB-INF/statlog.base.xml.template",
    ],
}

# xml文件中没有该bean
none_exist_beans = {
    "finance": ("authenticationManager", ),
    "mail": ("vendorUtil", "dataRack"),
    "channel": ("advDMEcpmRandomizer",),
    "audit2": ("authenticationManager", ),
    "adagent": ("authenticationManager", ),
    "account": ("authenticationManager", ),
    "dsp": ("dataRack", ),
}

self_refer_beans = {
    "dict": ("sizedImgInfoProvider",), # sizedImgInfoProvider refer its parent, which will lead to endless loop
    "union": ("dynamicApplicationContext",), 
}


class Bean:
    def __init__(self, id, name, class_name, xml_file):
        self.id = id
        self.name = name
        self.class_name = class_name
        self.ref_bean_list = []
        self.xml_file = xml_file


class BeanFilesParser:
    def __init__(self, deploy_root_dir, service_type):
        self.deploy_root_dir = deploy_root_dir
        self.service_type = service_type
        self.beansnode_list = []
        self.none_exist_bean_list = none_exist_beans.get(service_type, ())
        self.self_refer_bean_list = self_refer_beans.get(service_type, ())

    def read_xml_files(self):
        xml_file_list = xml_conf_dict[self.service_type]
        for xml_root_node, xml_file in get_xml_root_node_list(self.deploy_root_dir, xml_file_list):
            self.beansnode_list.append(NodeFactory.build_node("beans", xml_root_node).set_xml_file(xml_file))

    def output(self, file_name):
        for beans_node in self.beansnode_list:
            beans_node.output()

    # return bean_node(Node) based on given bean id
    def get_bean_node(self, bean_id):
        for beans_node in self.beansnode_list:
            for bean_node in beans_node.bean_list:
                if bean_node["id"] == bean_id:
                    return bean_node.set_xml_file(beans_node.xml_file) 

        if bean_id in self.none_exist_bean_list:
            return None

        raise Exception("Can't find bean with id: %s" % bean_id)

    # push bean into ref_bean_list of Bean
    def add_ref_bean_by_id(self, beanObj, bean_id):
        if bean_id and bean_id[0] != "$":
            bean_node = self.get_bean_node(bean_id)
            if bean_node == None:
                return

            bean = self.get_bean(bean_node)
            beanObj.ref_bean_list.append(bean)

    # return Bean based on given Node
    def get_bean(self, bean_node):
        bean_id = bean_node["id"]
        bean_name = bean_node["name"]
        bean_class = bean_node["class"]
        xml_file = bean_node.xml_file
        beanObj = Bean(bean_id, bean_name, bean_class, xml_file)

        # factory-bean in attrs
        self.add_ref_bean_by_id(beanObj, bean_node["factory-bean"])

        for property_node in bean_node.property_list:
            # ref in property attr attrs
            if property_node["ref"] in self.self_refer_bean_list:
                continue
            self.add_ref_bean_by_id(beanObj, property_node["ref"])

            # bean in property/ref attrs
            if property_node.ref:
                self.add_ref_bean_by_id(beanObj, property_node.ref["bean"])

            if property_node.map and property_node.map.entry_list:
                for entry_node in property_node.map.entry_list:
                    # bean in property/map/entry/ref attrs
                    if entry_node.ref:
                        self.add_ref_bean_by_id(beanObj, entry_node.ref["bean"])

                    # property/map/entry/bean
                    if entry_node.bean:
                        beanObj.ref_bean_list.append(self.get_bean(entry_node.bean.set_xml_file(bean_node.xml_file)))

            if property_node.list and property_node.list.ref_list:
                for ref_node in property_node.list.ref_list:
                    # bean in property/list/ref attrs
                    if ref_node["bean"] in self.self_refer_bean_list:
                        continue
                    self.add_ref_bean_by_id(beanObj, ref_node["bean"])

                for value_node in property_node.list.value_list:
                    # bean in property/list/values attrs
                    self.add_ref_bean_by_id(beanObj, value_node.innerValue)

                for bean_node2 in property_node.list.bean_list:
                    # bean in property/list/beans attrs
                    beanObj.ref_bean_list.append(self.get_bean(bean_node2.set_xml_file(bean_node.xml_file)))

        for constructor_arg in bean_node.constructor_arg_list:
            # ref in constructor-args attrs
            self.add_ref_bean_by_id(beanObj, constructor_arg["ref"])

            # bean in constructor-args attrs
            self.add_ref_bean_by_id(beanObj, constructor_arg["bean"])

            if constructor_arg.list:
                for bean_node2 in constructor_arg.list.bean_list:
                    # bean in constructor_arg/list/beans attrs
                    beanObj.ref_bean_list.append(self.get_bean(bean_node2.set_xml_file(bean_node.xml_file)))

        return beanObj

    def get_dict(self, bean, ret_dict):
        id = str(bean.id)
        name = str(bean.name)
        class_name = str(bean.class_name)
        xml_file = str(bean.xml_file)
        ref_bean_dict = {}
        key = class_name
        
        ### if key is None, then ignore it
        if key != "None":
            ret_dict[key] = (id, name, class_name, xml_file, ref_bean_dict)
            for ref_bean in bean.ref_bean_list:
                self.get_dict(ref_bean, ref_bean_dict)

    def print_json_result(self):
        bean_dict = {}
        for beans_node in self.beansnode_list:
            bean_dict[beans_node.xml_file] = {}
            for bean_node in beans_node.bean_list:
                bean = self.get_bean(bean_node.set_xml_file(beans_node.xml_file))
                self.get_dict(bean, bean_dict[beans_node.xml_file])

        print json.dumps(bean_dict)


if __name__ == "__main__":
    #beanFilesParser = BeanFilesParser("./m2.0", "finance")
    beanFilesParser = BeanFilesParser(sys.argv[1], sys.argv[2])
    beanFilesParser.read_xml_files()
    #beanFilesParser.output("abc.html")
    beanFilesParser.print_json_result()
