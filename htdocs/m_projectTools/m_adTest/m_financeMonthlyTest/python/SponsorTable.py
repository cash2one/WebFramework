#!/usr/bin/python
#encoding:utf-8

from BaseTable import *

class SponsorTable(BaseTable):
    def _my_init(self):
        self.db_conn = BaseTable.AdPublishDB
        self.table_name  = "Sponsor"
        self.table_desc  = "广告商信息表"
        self.sponsor_id_key_name = "SPONSOR_ID"

if __name__ == "__main__":
    table = SponsorTable()
    print table.get_sponsor_rows(64, ["*"])
