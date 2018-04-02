from tkinter import *
from PIL import Image, ImageTk
from tkinter import filedialog
import os
import threading

from photo import Photo
from monitorFiles import MonitorFiles

class Appli:

    images = []
    thumbnails = []
    photos = []
    path_images = []

    def __init__(self):
        self.root = Tk()
        self.root.title('Imprimez vos photos !')
        self.vsb = Scrollbar(self.root, orient=VERTICAL)
        self.vsb.grid(row=0, column=1, sticky=N+S)
        self.c = Canvas(self.root,yscrollcommand=self.vsb.set)
        self.c.grid(row=0, column=0, sticky="news")
        self.vsb.config(command=self.c.yview)
        self.root.grid_rowconfigure(0, weight=1)
        self.root.grid_columnconfigure(0, weight=1)
        self.fr = Frame(self.c)

        self.catalogue()

    def catalogue(self):
        self.catalogue = Frame(self.root, height=250, width=200, bg='red')
        self.catalogue.grid(row=0, column=2)
        
    def run(self):
        self.root.mainloop()

    def choose_dir(self):
        self.directory = filedialog.askdirectory()
        print(self.directory)

    def make_grid(self):
        i = 0
        self.update_images()
        for img in self.images:
            self.display_image(img, int(i / 3), int(i % 3))
            print(str(int(i/3)) + " " + str(int(i%3)))
            i = i + 1
                    
        self.c.create_window(0, 0,  window=self.fr)
        self.fr.update_idletasks()
        self.c.config(scrollregion=self.c.bbox("all"))

    def update_images(self):
        for f in os.listdir(self.directory):
            if f.endswith('.JPG'):
                path = self.directory + "/" + f
                if not path in self.path_images:
                    self.images.append(Image.open(path))
                    self.path_images.append(path)
                    print(path)
        self.root.after(2000, self.update_images)

    def display_image(self, image, row, column):
        self.photos.append(Photo(self.fr, image, row, column))
        self.photos[-1].grid(column = column, row = row)
