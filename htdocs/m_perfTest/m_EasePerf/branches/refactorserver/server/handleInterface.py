# encoding=utf-8
'''
Created on 2013-6-26
handle抽象接口，抽象了每个接口需要实现的函数
@author: 张波
'''

class handleInterface( object ):
    '''
    handle抽象接口，抽象了每个接口必须实现的函数
    '''


    def __init__( self ):
        '''
        Constructor
        '''
        pass
    
    def initHandle( self ):
        '''
        初始化函数
        '''
        raise 'Not implemented'

    def isValid( self ):
        '''
        handle 是否有效
        '''
        raise 'Not implemented'

    def generateKeys( self ):
        '''
        生成keys
        '''
        raise 'Not implemented'

    def getAlias( self ):
        '''
        获取别名
        '''
        raise 'Not implemented'

    def startMonitor( self ):
        '''
        监控入口函数
        '''
        raise 'Not implemented'
