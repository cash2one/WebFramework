#!/usr/bin/python
#encoding:utf-8

from Field import *

class SponsorMonthlyContractSchema:
    table_name = "SPONSOR_MONTHLY_CONTRACT"
    table_desc = "月结广告商合同信息"
    schema_fields= [
        Field("SMC_ID", Type.bigint, None),
        Field("SPONSOR_ID", Type.bigint, None),
        Field("CONTRACT_ID", Type.varchar, None),
        Field("CONTRACT_START_DATE", Type.bigint, None),
        Field("CONTRACT_END_DATE", Type.bigint, None),
        Field("CREDIT_POINTS", Type.bigint, None),
        Field("CONTRACT_TEXT_ID", Type.varchar, None),
        Field("STATUS", Type.int, 0),
        Field("REMARK", Type.varchar, None),
        Field("CREATE_TIME", Type.bigint, None),
        Field("LAST_UPDATE_TIME", Type.bigint, None),
        Field("CONSULTANT", Type.varchar, None),
        Field("FILE_NAME", Type.varchar, None),
        Field("CONFIRM_TIME", Type.bigint, None),
        Field("PARENT_ID", Type.bigint, -1),
    ]

class SponsorBalanceSchema:
    table_name = "SPONSOR_BALANCE"
    table_desc = "广告商余额表"
    schema_fields= [
        Field("SPONSOR_ID", Type.bigint, 0),
        Field("ACTUAL_BALANCE", Type.int, 0),
        Field("VIRTUAL_BALANCE", Type.int, 0),
        Field("DISCOUNT_RATE", Type.int, 10000),
        Field("DEPOSIT_STATUS", Type.int, 0),
        Field("CREDIT_LIMIT", Type.int, 0),
        Field("DEPOSIT_NUM", Type.int, None),
        Field("LAST_MOD_TIME", Type.bigint, 0),
        Field("CASH_FLOW", Type.int, 0),
        Field("SERVICE_CHARGE", Type.int, 0),
        Field("SERVICE_CHARGE_FLAG", Type.int, 0),
        Field("ACTUAL_AMOUNT_BALANCE", Type.int, 0),
        Field("VIRTUAL_AMOUNT_BALANCE", Type.int, 0),
        Field("DISCOUNT_TYPE", Type.int, None),
        Field("SETTLEMENT_TYPE", Type.int, None),
    ]

class SponsorMonthlyDiscountDetailSchema:
    table_name = "SPONSOR_MONTHLY_DISCOUNT_DETAIL"
    table_desc = "月结广告商折扣明细信息"
    schema_fields= [
        Field("SMDD_ID", Type.bigint, None),
        Field("SMD_ID", Type.bigint, None),
        Field("LOGIC_SYMBOL", Type.int, 0),
        Field("LEFT_OPERAND", Type.int, None),
        Field("RIGHT_OPERAND", Type.int, None),
        Field("DISCOUNT_RATE", Type.int, 10000),
        Field("STATUS", Type.int, 0),
    ]

class AdClickChargeUpProgressSchema:
    table_name = "AD_CLICK_CHARGE_UP_PROGRESS"
    table_desc = ""
    schema_fields= [
        Field("ACCUP_ID", Type.int, None),
        Field("FIN_SYS_ID", Type.int, 0),
        Field("PROGRESS_ACI_ID", Type.bigint, 0),
        Field("MAX_LOG_TIME", Type.bigint, 0),
        Field("REMARK", Type.varchar, None),
    ]

class LogDaSchema:
    table_name = "Log_DA"
    table_desc = "logdb DA的日志信息"
    schema_fields= [
        Field("statYear", Type.smallint, None),
        Field("statMonth", Type.int, None),
        Field("statDay", Type.int, None),
        Field("statWeekDay", Type.smallint, None),
        Field("adAgentId", Type.int, None),
        Field("adSponsorId", Type.int, None),
        Field("adCampaignId", Type.bigint, None),
        Field("adGroupId", Type.bigint, None),
        Field("adContentId", Type.bigint, None),
        Field("channelTypeId", Type.smallint, None),
        Field("showNum", Type.bigint, None),
        Field("showPos", Type.bigint, None),
        Field("clickNum", Type.int, None),
        Field("consumption", Type.int, None),
        Field("convertNum", Type.int, None),
        Field("convertValue", Type.int, None),
        Field("id", Type.varchar, None),
    ]

class SponsorBalanceChangeSchema:
    table_name = "SPONSOR_BALANCE_CHANGE"
    table_desc = "广告商余额变化表"
    schema_fields= [
        Field("SBC_ID", Type.bigint, None),
        Field("SPONSOR_ID", Type.bigint, 0),
        Field("CHANGE_AMOUNT", Type.int, 0),
        Field("CHANGE_TYPE", Type.int, 0),
    ]

class SponsorSchema:
    table_name = "Sponsor"
    table_desc = "广告商信息表"
    schema_fields= [
        Field("SPONSOR_ID", Type.bigint, None),
        Field("NAME", Type.varchar, None),
        Field("TYPE", Type.int, None),
        Field("INDUSTRY_TYPE", Type.int, None),
        Field("SUB_INDUSTRY_TYPE", Type.int, 0),
        Field("AUDIENCE", Type.int, 1),
        Field("STATUS", Type.int, None),
        Field("PROVINCE", Type.int, None),
        Field("CITY", Type.int, None),
        Field("IF_RECEIVE_EMAIL", Type.int, None),
        Field("IF_ACCEPT_OPTIMIZATION", Type.int, None),
        Field("LICENSE_NUM", Type.varchar, None),
        Field("TAX_NUM", Type.varchar, None),
        Field("CREATE_TIME", Type.bigint, None),
        Field("CLOSE_TIME", Type.bigint, None),
        Field("LAST_MOD_TIME", Type.bigint, None),
        Field("CONTACT_ID", Type.bigint, None),
        Field("WEB_URL", Type.varchar, None),
        Field("USER_NAME", Type.varchar, None),
        Field("AGENT_ID", Type.bigint, None),
        Field("STOP_ACCOUNT_TIME", Type.bigint, 0),
        Field("AUDIT_STATUS", Type.int, 0),
        Field("EMAIL", Type.varchar, None),
        Field("CPC_ACCOUNT", Type.varchar, None),
        Field("CPC_ID", Type.bigint, None),
        Field("VIRTUAL_DELIVERY_TYPE", Type.int, None),
        Field("QUALITY_LEVEL", Type.int, None),
        Field("VIRTUAL_STATUS", Type.int, 31),
        Field("STOP_STATUS", Type.int, 0),
    ]

class LogDaSimSchema:
    table_name = "Log_DA_SIM"
    table_desc = "logdb DA_SIM的日志信息"
    schema_fields= [
        Field("statYear", Type.smallint, None),
        Field("statMonth", Type.int, None),
        Field("statDay", Type.int, None),
        Field("statWeekDay", Type.smallint, None),
        Field("adAgentId", Type.int, None),
        Field("adSponsorId", Type.int, None),
        Field("channelTypeId", Type.smallint, None),
        Field("showNum", Type.bigint, None),
        Field("clickNum", Type.int, None),
        Field("consumption", Type.int, None),
        Field("convertNum", Type.int, None),
        Field("convertValue", Type.int, None),
        Field("showPos", Type.bigint, None),
        Field("id", Type.varchar, None),
    ]

class SponsorAccountHistorySchema:
    table_name = "SPONSOR_ACCOUNT_HISTORY"
    table_desc = "广告商账务明细表"
    schema_fields= [
        Field("SAH_ID", Type.bigint, None),
        Field("FROM_USER_ID", Type.bigint, 0),
        Field("TO_USER_ID", Type.bigint, 0),
        Field("OPERATE_TYPE", Type.int, 0),
        Field("CREDIT_AMOUNT", Type.int, 0),
        Field("ACTUAL_BALANCE", Type.int, 0),
        Field("VIRTUAL_BALANCE", Type.int, 0),
        Field("COUPON", Type.varchar, 0),
        Field("NEW_ACTUAL_BAL", Type.int, None),
        Field("NEW_VIRTUAL_BAL", Type.int, None),
        Field("DEPOSIT_SEQ", Type.int, None),
        Field("OPERATOR_ID", Type.varchar, 0),
        Field("OPERATE_TIME", Type.bigint, 0),
        Field("OPERATOR_IP", Type.varchar, None),
        Field("AUDITOR_ID", Type.varchar, 0),
        Field("AUDIT_STATUS", Type.int, 0),
        Field("AUDIT_TIME", Type.bigint, 0),
        Field("IS_CHARGED", Type.int, 0),
        Field("PAYMENT_TYPE", Type.int, 0),
        Field("BILL_NO", Type.varchar, None),
        Field("VOUCHER_NO", Type.varchar, None),
        Field("REMARK", Type.varchar, None),
        Field("CASH_FLOW", Type.int, 0),
        Field("NEW_CASH_FLOW", Type.int, 0),
        Field("OPERATE_ID", Type.bigint, None),
    ]

class SponsorMonthlyDiscountSchema:
    table_name = "SPONSOR_MONTHLY_DISCOUNT"
    table_desc = "月结广告商折扣信息"
    schema_fields= [
        Field("SMD_ID", Type.bigint, None),
        Field("SPONSOR_ID", Type.bigint, None),
        Field("STATUS", Type.int, 0),
        Field("AUDIT_STATUS", Type.int, 1),
        Field("COMMENT", Type.varchar, None),
        Field("CREATE_TIME", Type.bigint, None),
        Field("AUDIT_TIME", Type.bigint, None),
        Field("DISCOUNT_TYPE", Type.int, None),
    ]

class SponsorMonthlySettlementSchema:
    table_name = "SPONSOR_MONTHLY_SETTLEMENT"
    table_desc = "广告商月结信息"
    schema_fields= [
        Field("SMS_ID", Type.bigint, None),
        Field("SPONSOR_ID", Type.bigint, None),
        Field("SETTLEMNET_MONTH", Type.int, None),
        Field("TOTAL_COMSUMPTION", Type.int, 0),
        Field("TOTAL_CONSUMPTION_OF_INCOME", Type.int, 0),
        Field("VIRTUAL_AMOUNT_BALANCE", Type.int, 0),
        Field("DISCOUNT_RATE", Type.int, None),
        Field("ACTUAL_AMOUNT_BALANCE", Type.int, 0),
        Field("TOTAL_INCOME", Type.int, 0),
        Field("TO_BE_PAID", Type.int, 0),
        Field("PAYMENT_STATUS", Type.int, 1),
        Field("AUDIT_STATUS", Type.int, 0),
        Field("SAH_ID", Type.bigint, None),
        Field("CREATE_TIME", Type.bigint, None),
        Field("SETTLEMENT_TIME", Type.bigint, None),
        Field("AUDIT_TIME", Type.bigint, None),
        Field("CONFIRM_MONTH", Type.int, 0),
    ]

class ClickSchema:
    table_name = "CLICK"
    table_desc = ""
    schema_fields= [
        Field("ID", Type.bigint, None),
        Field("SPONSOR_ID", Type.bigint, 0),
        Field("CAMPAIGN_ID", Type.bigint, 0),
        Field("ADGROUP_ID", Type.bigint, 0),
        Field("ADVARIATION_ID", Type.bigint, 0),
        Field("KEYWORD_ID", Type.bigint, 0),
        Field("SYNDICATION_ID", Type.bigint, 0),
        Field("SITE_ID", Type.bigint, 0),
        Field("IMPR_POS", Type.int, 0),
        Field("QUALITY_SCORE", Type.float, 0),
        Field("RANK", Type.float, 0),
        Field("ORIG_COST", Type.int, 0),
        Field("ACTU_COST", Type.int, 0),
        Field("IMPR_IP", Type.varchar, None),
        Field("CLICKER_IP", Type.varchar, None),
        Field("CLICKER_ID", Type.bigint, None),
        Field("IMPR_TIME", Type.timestamp, '0000-00-00 00:00:00'),
        Field("CLICK_TIME", Type.timestamp, '0000-00-00 00:00:00'),
        Field("COMMIT_TIME", Type.timestamp, 'CURRENT_TIMESTAMP'),
        Field("IMPR_REQ", Type.varchar, None),
        Field("REFERER", Type.varchar, None),
        Field("MORE_INFO", Type.varchar, None),
        Field("CODE_ID", Type.bigint, 0),
        Field("MEMBER_ID", Type.bigint, 0),
    ]


