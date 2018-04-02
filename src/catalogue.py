from tkinter import *
from PIL import Image, ImageTk

from photobuy import PhotoBuy

class Catalog():
    img = []
    nb = 0
    ind = 0
   
    def __init__(self, parent):
        self.parent = parent

    def add_picture(self, img):
        self.img.append(PhotoBuy(self.parent, img, self.nb, 0, self.ind, self.print_ind))
        self.nb = self.nb + 1
        self.ind = self.ind + 1

    def print_ind(self, name, qte):
        print("print from catalog : " + str(name) + " " + str(qte))
        print("print from photo : ")
        new_qte = self.img[int(name)].change_qty(name, qte)

        if new_qte==0:
            self.img[int(name)].remove()
            self.nb = self.nb - 1
            print("remove element " + str(self.nb))
