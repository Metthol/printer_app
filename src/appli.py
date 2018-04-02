from tkinter import *
from PIL import Image, ImageTk
from tkinter import filedialog
import os

from photo import Photo

class Appli:

    images = []
    thumbnails = []
    photos = []
    path_images = []

    def __init__(self):
        self.root = Tk()
        self.root.title('Imprimez vos photos !')
        self.root.minsize(500, 500)
        self.root.geometry('500x500')
        self.vsb = Scrollbar(self.root, orient=VERTICAL)
        self.vsb.grid(row=0, column=1, sticky=N+S)
        self.c = Canvas(self.root,yscrollcommand=self.vsb.set)
        self.c.grid(row=0, column=0, sticky="news")
        self.vsb.config(command=self.c.yview)
        self.root.grid_rowconfigure(0, weight=1)
        self.root.grid_columnconfigure(0, weight=1)
        self.fr = Frame(self.c)
        
    def run(self):
        self.root.mainloop()

    def load_images(self):
        for filename in os.listdir(self.directory):
            if filename.endswith('.JPG'):
                path = self.directory + "/" + filename
                self.images.append(Image.open(path))
                self.path_images.append(path)
                print(path)

    def choose_dir(self):
        self.directory = filedialog.askdirectory()
        print(self.directory)
        self.load_images()

    def make_grid(self):
        i = 0
        for img in self.images:
            self.display_image(img, int(i / 3), int(i % 3))
            print(str(int(i/3)) + " " + str(int(i%3)))
            i = i + 1
            
        print(self.fr.grid_info())
        self.c.create_window(0, 0,  window=self.fr)
        self.fr.update_idletasks()
        self.c.config(scrollregion=self.c.bbox("all"))

    def display_image(self, image, row, column):
        self.photos.append(Photo(self.fr, image, row, column))
        self.photos[-1].grid(column = column, row = row)
