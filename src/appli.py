from tkinter import *
from PIL import Image, ImageTk
from tkinter import filedialog
import os

class Appli:

    images = []
    thumbnails = []
    path_images = []

    def __init__(self):
        self.fenetre = Tk()
        
        self.fenetre.title('Imprimez vos photos !')
        self.fenetre.geometry('500x500') # Size 200, 200
        self.frame = Frame(self.fenetre, height=500, width=500)
        self.frame.pack()

    def run(self):
        self.fenetre.mainloop()

    def load_images(self):
        for filename in os.listdir(self.directory):
            if filename.endswith('.jpg'):
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

    def display_image(self, image, row, column):
        self.thumbnails.append(image)
        w, h = self.thumbnails[-1].size
        size = 150, h * 150 / w
        self.thumbnails[-1].thumbnail(size, Image.ANTIALIAS)

        self.thumbnails[-1] = ImageTk.PhotoImage(self.thumbnails[-1])

        color = 'red'
        if (row + column) % 2 == 0:
            color = 'blue'
            
        canvas = Canvas(self.frame, width=200, height=200, bg=color)
        
        canvas.create_image(10, 10, anchor=NW, image=self.thumbnails[-1])
        canvas.grid(row = row, column = column)

    
