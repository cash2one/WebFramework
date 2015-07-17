#!/usr/bin/python

import Queue

from ThreadPool import *
from util import *

hosts = get_hosts()
queue = Queue.Queue()
tp = ThreadPool(queue, len(hosts))

for hostname in hosts:
    tp.add_job(read_result, hostname)

tp.wait_for_complete()
