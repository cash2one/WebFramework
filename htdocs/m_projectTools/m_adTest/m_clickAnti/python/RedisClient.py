#!/usr/bin/python
### refer: http://redis.io/clients

import redis
from Config import *

class RedisClient:
    def __init__(self):
        self._redisClient = redis.StrictRedis(host = REDIS_HOST, port = REDIS_PORT)

    def clear(self):
        self._redisClient.flushdb()

    def dump(self):
        for k in self._redisClient.keys():
            print k, ":", self._redisClient[k]

    def delete_key(self, key):
        self._redisClient.delete(key)

    def get_size(self):
        return self._redisClient.dbsize()

    def get(self, key):
        return self._redisClient.get(key)

    def set(self, key, val):
        self._redisClient[key] = val

    def exists(self, key):
        return self._redisClient.exists(key)

    def keys(self):
        return self._redisClient.keys()


if __name__ == "__main__":
    rc = RedisClient()
    rc.clear()
    rc.dump()
