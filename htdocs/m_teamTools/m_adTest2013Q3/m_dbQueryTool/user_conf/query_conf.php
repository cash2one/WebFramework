<?php

$queries = Array(
    /*
     * added by zhangpei
     */
    Array(
        "id" => "qry-001",
        "users" => Array("zhangpei"),
        "refer_db_info" => Array("sponsor_1"),
        "cate_list" => Array("sponsor", "agent"),
        "title_name" => "代理商id查询",
        "desc"       => "根据sponsor_id查询对应的agent_id",
        "query_field_names" => Array("SPONSOR_ID"),
        "result_field_names" => Array("AGENT_ID"),
    ),
    Array(
        "id" => "qry-002",
        "users" => Array("zhangpei"),
        "refer_db_info" => Array("sponsor_1"),
        "cate_list" => Array("sponsor"),
        "title_name" => "广告商信息查询",
        "desc"       => "根据广告商的id查询其名称,用户名和状态",
        "query_field_names" => Array("SPONSOR_ID"),
        "result_field_names" => Array("NAME", "USER_NAME", "STATUS"),
    ),
    Array(
        "id" => "qry-003",
        "query_type" => "OneOrSelect",
        "users" => Array("zhangpei"),
        "refer_db_info" => Array("sponsor_1"),
        "cate_list" => Array("sponsor"),
        "title_name" => "广告商id查询",
        "desc"       => "根据广告商的名称/用户名查询其id",
        "query_field_names" => Array("USER_NAME", "NAME"),
        "result_field_names" => Array("SPONSOR_ID"),
    ),
    Array(
        "id" => "qry-005",
        "query_type" => "titleValueMap",
        "users" => Array("zhangpei"),
        "refer_db_info" => Array("finance_sponsor"),
        "cate_list" => Array("finance", "sponsor"),
        "title_name" => "广告商财务信息查询",
        "desc"       => "根据广告商的id查询其财务相关信息",
        "query_field_names" => Array("SPONSOR_ID"),
        "result_field_names" => Array("ACTUAL_BALANCE", "VIRTUAL_BALANCE", "DISCOUNT_RATE", "CREDIT_LIMIT", "ACTUAL_AMOUNT_BALANCE", "VIRTUAL_AMOUNT_BALANCE", "DISCOUNT_TYPE", "SETTLEMENT_TYPE"),
    ),
    Array(
        "id" => "qry-006",
        "users" => Array("zhangpei"),
        "refer_db_info" => Array("industry_1"),
        "cate_list" => Array("industry"),
        "title_name" => "行业信息查询",
        "desc"       => "根据行业id查询其名称",
        "query_field_names" => Array("ID"),
        "result_field_names" => Array("NAME"),
    ),
    Array(
        "id" => "qry-007",
        "users" => Array("zhangpei"),
        "refer_db_info" => Array("industry_2"),
        "cate_list" => Array("industry"),
        "title_name" => "子行业信息查询",
        "desc"       => "根据子行业id查询其名称",
        "query_field_names" => Array("ID"),
        "result_field_names" => Array("NAME", "PARENT_ID"),
    ),
    Array(
        "id" => "qry-008",
        "users" => Array("zhangpei"),
        "refer_db_info" => Array("industry_2"),
        "cate_list" => Array("industry"),
        "title_name" => "行业查子行业",
        "desc"       => "根据行业id查询其子行业和名称",
        "query_field_names" => Array("PARENT_ID"),
        "result_field_names" => Array("ID", "NAME"),
    ),
    Array(
        "id" => "qry-009",
        "users" => Array("zhangpei"),
        "refer_db_info" => Array("industry_3"),
        "cate_list" => Array("industry", "sponsor"),
        "title_name" => "广告商行业信息查询",
        "desc"       => "根据广告商id查询其行业、子行业",
        "query_field_names" => Array("SPONSOR_ID"),
        "result_field_names" => Array("INDUSTRY_TYPE", "SUB_INDUSTRY_TYPE"),
    ),
    Array(
        "id" => "qry-010",
        "query_type" => "titleValueMap",
        "users" => Array("zhangpei"),
        "refer_db_info" => Array("click_1"),
        "cate_list" => Array("click"),
        "title_name" => "反作弊配置查询",
        "desc"       => "根绝server_id(1-7)查询其相关信息",
        "query_field_names" => Array("SERVER_ID"),
        "result_field_names" => Array("SERVER_ID", "FILTER_NAME", "VALUE", "FKEY"),
    ),
    Array(
        "id" => "qry-011",
        "users" => Array("zhangpei"),
        "refer_db_info" => Array("sponsor_2"),
        "cate_list" => Array("agent"),
        "title_name" => "代理商名称、邮件查询",
        "desc"       => "根据agent_id查询对应的信息",
        "query_field_names" => Array("AGENT_ID"),
        "result_field_names" => Array("NAME", "EMAIL"),
    ),
    Array(
        "id" => "qry-012",
        "users" => Array("zhangpei"),
        "refer_db_info" => Array("sponsor_2"),
        "cate_list" => Array("agent"),
        "title_name" => "代理商ID查询",
        "desc"       => "根据代理商名称查询对应的ID",
        "query_field_names" => Array("NAME"),
        "result_field_names" => Array("AGENT_ID"),
    ),
    Array(
        "id" => "qry-013",
        "users" => Array("zhangpei"),
        "refer_db_info" => Array("ad_1"),
        "cate_list" => Array("ad"),
        "title_name" => "广告变体相关信息查询",
        "desc"       => "根据变体ID查询其状态、sponsor_id，group_id",
        "query_field_names" => Array("AD_CONTENT_ID"),
        "result_field_names" => Array("STATUS", "SPONSOR_ID", "AD_GROUP_ID"),
    ),
    Array(
        "id" => "qry-014",
        "users" => Array("zhangpei"),
        "refer_db_info" => Array("ad_3"),
        "cate_list" => Array("ad"),
        "title_name" => "广告组相关信息查询",
        "desc"       => "根据组ID查询其状态、sponsor_id，campaign_id",
        "query_field_names" => Array("AD_GROUP_ID"),
        "result_field_names" => Array("STATUS", "SPONSOR_ID", "AD_CAMPAIGN_ID"),
    ),
    Array(
        "id" => "qry-015",
        "users" => Array("zhangpei"),
        "refer_db_info" => Array("ad_2"),
        "cate_list" => Array("ad"),
        "title_name" => "广告系列相关信息查询",
        "desc"       => "根据系列ID查询其状态、sponsor_id，plan_id",
        "query_field_names" => Array("AD_CAMPAIGN_ID"),
        "result_field_names" => Array("STATUS", "SPONSOR_ID", "AD_PLAN_ID"),
    ),
    Array(
        "id" => "qry-016",
        "query_type" => "titleValueMap",
        "users" => Array("zhangpei"),
        "refer_db_info" => Array("finance_agent"),
        "cate_list" => Array("finance", "agent"),
        "title_name" => "代理商财务信息查询",
        "desc"       => "根据代理商的id查询其财务相关信息",
        "query_field_names" => Array("AGENT_ID"),
        "result_field_names" => Array("ACTUAL_BALANCE", "VIRTUAL_BALANCE"),
    ),
    Array(
        "id" => "qry-017",
        "query_type" => "noInput",
        "users" => Array("zhangpei"),
        "refer_db_info" => Array("sponsor_1"),
        "cate_list" => Array("sponsor", "dsp"),
        "title_name" => "DSP广告商信息查询",
        "desc"       => "查询DSP广告商信息",
        "query_field_names" => Array("PRODUCT_ACCESSIBLE in (2, 3)"),
        "result_field_names" => Array("SPONSOR_ID", "NAME", "USER_NAME", "AGENT_ID", "PRODUCT_ACCESSIBLE"),
    ),
);
