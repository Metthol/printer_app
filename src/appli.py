from tkinter import *
from PIL import Image, ImageTk
from tkinter import filedialog
import os
import threading

from photo import Photo
from catalogue import Catalog

class Appli:

    images = []
    thumbnails = []
    photos = []
    path_images = []
    di = 0

    def __init__(self):
        self.root = Tk()
        self.root.configure(background='white')
        self.root.title('Imprimez vos photos !')
        
        self.vsb = Scrollbar(self.root, orient=VERTICAL)
        self.vsb.grid(row=0, column=1, sticky=N+S)
        
        self.c = Canvas(self.root,yscrollcommand=self.vsb.set)
        self.c.grid(row=0, column=0, sticky="news")
        self.c.configure(background='white')

        self.vsb.config(command=self.c.yview)

        self.root.grid_rowconfigure(0, weight=1)
        self.root.grid_columnconfigure(0, weight=1)
        self.fr = Frame(self.c)
        
        self.catalogue()

    def catalogue(self):
        self.cvsb = Scrollbar(self.root, orient = VERTICAL)
        self.cvsb.grid(row=0, column=3, sticky=N+S)

        self.cc = Canvas(self.root, yscrollcommand=self.cvsb.set)
        self.cc.grid(row=0, column=2, sticky="news")
        self.cc.configure(background='white')

        self.cvsb.config(command=self.cc.yview)

        self.root.grid_rowconfigure(0, weight=1)
        self.root.grid_columnconfigure(0, weight=1)
        
        self.cfr = Frame(self.cc)

        self.catalogue = Catalog(self.cfr, self.root)

        self.print_button = Button(self.cc, text ="Imprimer", command=self.catalogue.print)
        self.print_button.grid(row=0, column=0, padx=(150,150))
        

    def add_picture(self, name, qte):
        print(name + " " + str(qte))
        self.catalogue.add_picture(int(name), self.images[int(name)], qte)
        self.fr.update_idletasks()

        self.cc.create_window(0, 0, window=self.cfr)
        self.cfr.update_idletasks()
        self.cc.config(scrollregion=self.cc.bbox("all"))
        
    def run(self):
        self.root.mainloop()

    def choose_dir(self):
        self.directory = filedialog.askdirectory()
        print(self.directory)

    def make_grid(self):
        self.update_images()
        for img in self.images[self.di:]:
            self.display_image(img, int(self.di / 3), int(self.di % 3))
            print(str(int(self.di/3)) + " " + str(int(self.di%3)))
            self.di = self.di + 1
                    
        self.c.create_window(0, 0, window=self.fr)
        self.fr.update_idletasks()
        self.c.config(scrollregion=self.c.bbox("all"))

        self.root.after(2000, self.make_grid)


    def update_images(self):
        for f in os.listdir(self.directory):
            if f.endswith('.JPG') or f.endswith('.jpg'):
                path = self.directory + "/" + f
                if not path in self.path_images:
                    self.images.append(Image.open(path))
                    self.path_images.append(path)
                    print(path)

    def display_image(self, image, row, column):
        self.photos.append(Photo(self.fr, image, row, column, self.di, self.add_picture))
