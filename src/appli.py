from tkinter import *
from PIL import Image, ImageTk

from tkinter import filedialog

class Appli:

    def __init__(self):
        self.fenetre = Tk()

    def display_image(self):
        img = Image.open('out.jpg')
        
        w, h = img.size
        size = 150, h * 150 / w
        img.thumbnail(size, Image.ANTIALIAS)

        self.imgg = ImageTk.PhotoImage(img)

        canvas = Canvas(self.fenetre, width=200, height=200, bg='red')
        canvas.create_image(10, 10, anchor=NW, image=self.imgg)
        canvas.pack()

    def run(self):
        self.fenetre.mainloop()

    def choose_dir(self):
        self.directory = filedialog.askdirectory()
