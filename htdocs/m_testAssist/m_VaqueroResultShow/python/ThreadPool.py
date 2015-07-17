#!/usr/bin/python
#coding:utf-8
# refer html: http://www.cnblogs.com/nsnow/archive/2010/04/18/1714596.html

import Queue
import threading
import sys
import time
import urllib

from util import *

#替我们工作的线程池中的线程
class MyThread(threading.Thread):
    def __init__(self, workQueue, **kwargs):
        threading.Thread.__init__(self, kwargs = kwargs)

        #线程在结束前等待任务队列多长时间
        self.setDaemon(True)
        self.workQueue = workQueue
        self.start()

    def run(self):
        #从工作队列中获取一个任务
        callable, args, kargs = self.workQueue.get()

        while True:
            try:
                #我们要执行的任务
                res = callable(args[0])

            except :
                print "###", sys.exc_info()
                raise

class ThreadPool:
    def __init__(self, queue, num_of_threads = 10):
        self.threads = []
        self.workQueue = queue
        self.__createThreadPool(num_of_threads)

    def __createThreadPool(self, num_of_threads):
        for i in range(num_of_threads):
            thread = MyThread(self.workQueue)
            self.threads.append(thread)

    def wait_for_complete(self):
        #等待所有线程完成。
        while len(self.threads):
            thread = self.threads.pop()

            #等待线程结束
            if thread.isAlive():#判断线程是否还存活来决定是否调用join
                thread.join()

    def add_job(self, callable, *args, **kwargs):
        self.workQueue.put((callable, args, kwargs))


if __name__ == '__main__':
    test()
