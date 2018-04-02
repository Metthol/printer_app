from tkinter import *
from PIL import Image, ImageTk

from photobuy import PhotoBuy

class Catalog():
    img = []
    nb = 0
   
    def __init__(self, parent):
        self.parent = parent
        self.fr = Frame(parent)
        self.fr.grid(row=0, column=2)

    def add_picture(self, img):
        self.img.append(PhotoBuy(self.fr, img, self.nb, 0, self.nb, self.print_ind))
        self.nb = self.nb + 1

    def print_ind(self, name, qte):
        print("print from catalog : " + str(name) + " " + str(qte))
