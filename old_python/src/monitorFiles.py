import sys
from threading import Thread
import time
import os

class MonitorFiles(Thread):

    directory = ""
    paths = []
    new_files = []

    def __init__(self, path, lock, appli):
        Thread.__init__(self)
        self.directory = path
        self.lock = lock
        self.appli = appli
        
        for filename in os.listdir(self.directory):
            if filename.endswith('.JPG') or filename.endswith('.jpg'):
                self.paths.append(filename)

    def run(self):
        while(True):
            self.lock.acquire()
            for f in os.listdir(self.directory):
                if not f in self.paths:
                    new_files.append(f)
            self.lock.release()
            self.appli.make_grid()
            
            time.sleep(5)
        
