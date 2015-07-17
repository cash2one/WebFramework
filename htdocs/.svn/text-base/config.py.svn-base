#!/usr/bin/python

import os
import sys

class Worker:
    def __init__(self):
        self.UrlList = []

    def add(self, urlObj):
        self.UrlList.append(urlObj)

    def doWork(self):
        for url in self.UrlList:
            url.doWork()

class Url:
    downloadDir = "downloads"
    js_dir      = "js-base"
    css_dir     = "css-base"

    def __init__(self, url, filename, link_target, link_filename):
        self.url         = url
        self.filename    = filename
        self.link_target = link_target
        self.link_filename= link_filename

    def _run_cmd(self, cmd):
        print "Cmd: %s" % cmd
        ret = os.system(cmd + ">/dev/null")
        self._check_ret(ret)

    def _check_ret(self, ret):
        if ret != 0:
            sys.exit("Result: Fail")
        else:
            print "Result: Pass"

    def _file_exists(self):
        return os.path.exists("%s/%s" % (Url.downloadDir, self.filename))

    def _download(self):
        if not self._file_exists():
            cmd = "cd %s; wget '%s' -O %s" % (Url.downloadDir, self.url, self.filename)
            self._run_cmd(cmd)

    def _remove_link(self, path):
        cmd = "rm -f %s" % path
        os.system(cmd)

    def _unzip(self):
        pass

    def _setlink(self):
        if self.link_target.endswith(".css"):
            self._remove_link("%s/%s" % (self.css_dir, self.link_filename))
            cmd = "cd %s; ln -s ../%s/%s %s" % (Url.css_dir, self.downloadDir, self.link_target, self.link_filename)
            self._run_cmd(cmd)

        else:
            self._remove_link("%s/%s" % (self.js_dir, self.link_filename))
            cmd = "cd %s; ln -s ../%s/%s %s" % (Url.js_dir, self.downloadDir, self.link_target, self.link_filename)
            self._run_cmd(cmd)


    def doWork(self):
        self._download()
        self._unzip()
        self._setlink()

    def register(self, worker):
        worker.add(self)


class ZipUrl(Url):
    def __init__(self, url, zip_filename, filename, link_target, link_filename):
        self.url = url
        self.zip_filename = zip_filename
        self.filename = filename
        self.link_target = link_target
        self.link_filename= link_filename

    def _download(self):
        if not self._file_exists():
            cmd = "cd %s; wget '%s' -O %s" % (Url.downloadDir, self.url, self.zip_filename)
            self._run_cmd(cmd)

    def _file_exists(self):
        return os.path.exists("%s/%s" % (Url.downloadDir, self.zip_filename))

    def _unzip(self):
        cmd = "cd %s; rm -rf %s" % (self.downloadDir, self.filename)
        self._run_cmd(cmd)

        cmd = "cd %s; unzip %s" % (Url.downloadDir, self.zip_filename)
        self._run_cmd(cmd)


class Url2(Url):
    def _download(self):
        pass

    def _unzip(self):
        pass


class ZipUrl2(ZipUrl):
    def _download(self):
        pass

    def _unzip(self):
        pass

class ZipUrl3(ZipUrl):
    def _download(self):
        if not self._file_exists():
            cmd = "cd %s; wget '%s' -O %s" % (Url.downloadDir, self.url, self.zip_filename)
            self._run_cmd(cmd)

    def _unzip(self):
        cmd = "cd %s; rm -rf %s" % (self.downloadDir, self.filename)
        self._run_cmd(cmd)

        cmd = "cd %s; mkdir %s; unzip %s -d %s" % (Url.downloadDir, self.filename, self.zip_filename, self.filename)
        self._run_cmd(cmd)
        

if __name__ == "__main__":
    worker = Worker()

    Url("http://code.jquery.com/jquery-1.8.2.min.js", "jquery-1.8.2.min.js", "jquery-1.8.2.min.js", "jquery.min.js").register(worker)
    Url("http://jquery-json.googlecode.com/files/jquery.json-2.4.min.js", "jquery.json-2.4.min.js", "jquery.json-2.4.min.js", "json.min.js").register(worker)
    Url("http://www.trendskitchens.co.nz/jquery/contextmenu/jquery.contextmenu.r2.packed.js", "jquery.contextmenu.r2.packed.js", "jquery.contextmenu.r2.packed.js", "contextmenu.min.js").register(worker)
    ZipUrl("http://yui.zenfs.com/releases/yui3/yui_3.7.3.zip", "yui_3.7.3.zip", "yui", "yui/build/yui",  "yui").register(worker)

    ZipUrl("http://jqueryui.com/resources/download/jquery-ui-1.9.1.custom.zip", "jquery-ui-1.9.1.custom.zip", "jquery-ui-1.9.1.custom", "jquery-ui-1.9.1.custom/js/jquery-ui-1.9.1.custom.min.js", "jquery-ui.min.js").register(worker)
    ZipUrl2("http://jqueryui.com/resources/download/jquery-ui-1.9.1.custom.zip", "jquery-ui-1.9.1.custom.zip", "jquery-ui-1.9.1.custom", "jquery-ui-1.9.1.custom/css/smoothness/jquery-ui-1.9.1.custom.min.css", "jquery-ui.min.css").register(worker)

    ZipUrl("https://github.com/isocra/TableDnD/archive/master.zip", "TableDnD-master.zip", "TableDnD-master", "TableDnD-master/stable/jquery.tablednd.js", "tablednd.min.js").register(worker)
    ZipUrl("https://github.com/alpixel/jMenu/archive/master.zip", "jMenu-master.zip", "jMenu-master", "jMenu-master", "jmenu").register(worker)
    ZipUrl3("http://apycom.com/menus/1-deep-sky-blue.zip", "1-deep-sky-blue.zip", "jMenu-apycom", "jMenu-apycom", "jmenu-apycom").register(worker)
    
    worker.doWork()
