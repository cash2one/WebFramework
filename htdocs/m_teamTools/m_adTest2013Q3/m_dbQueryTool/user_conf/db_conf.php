<?php

$conf_list = Array(
    "sb111:3306" => Array("eadonline4nb", "new1ife4Th1sAugust"),
    "tb011:3306" => Array("eadonline4nb", "new1ife4Th1sAugust"),
    "clkdb-reader1:3306" => Array("eadonline4nb", "new1ife4Th1sAugust"),
);

$db_info = Array(
    "sponsor_1" => Array(
        "db_type" => "mysql",
        "db_host" => "sb111",
        "db_port" => "3306",
        "db_name" => "eadb1",
        "table_name" => "Sponsor",
    ),
    "sponsor_2" => Array(
        "db_type" => "mysql",
        "db_host" => "sb111",
        "db_port" => "3306",
        "db_name" => "eadb1",
        "table_name" => "Agent",
    ),
    "finance_sponsor" => Array(
        "db_type" => "mysql",
        "db_host" => "tb011",
        "db_port" => "3306",
        "db_name" => "eadb3",
        "table_name" => "SPONSOR_BALANCE",
    ),
    "finance_agent" => Array(
        "db_type" => "mysql",
        "db_host" => "tb011",
        "db_port" => "3306",
        "db_name" => "eadb3",
        "table_name" => "AGENT_BALANCE",
    ),
    "industry_1" => Array(
        "db_type" => "mysql",
        "db_host" => "tb011",
        "db_port" => "3306",
        "db_name" => "industry",
        "table_name" => "IndustryType",
    ),
    "industry_2" => Array(
        "db_type" => "mysql",
        "db_host" => "tb011",
        "db_port" => "3306",
        "db_name" => "industry",
        "table_name" => "SubIndustryType",
    ),
    "industry_3" => Array(
        "db_type" => "mysql",
        "db_host" => "tb011",
        "db_port" => "3306",
        "db_name" => "industry",
        "table_name" => "IndustryInfo",
    ),
    "click_1" => Array(
        "db_type" => "mysql",
        "db_host" => "clkdb-reader1",
        "db_port" => "3306",
        "db_name" => "eadb11",
        "table_name" => "FILTERARG",
    ),
    "ad_1" => Array(
        "db_type" => "mysql",
        "db_host" => "sb111",
        "db_port" => "3306",
        "db_name" => "eadb1",
        "table_name" => "AdContent",
    ),
    "ad_2" => Array(
        "db_type" => "mysql",
        "db_host" => "sb111",
        "db_port" => "3306",
        "db_name" => "eadb1",
        "table_name" => "AdCampaign",
    ),
    "ad_3" => Array(
        "db_type" => "mysql",
        "db_host" => "sb111",
        "db_port" => "3306",
        "db_name" => "eadb1",
        "table_name" => "AdGroup",
    ),
);
