from tkinter import *
from PIL import Image, ImageTk

from photobuy import PhotoBuy

class Catalog():
    img = []
    names = []
    nb = 0
    ind = 0
   
    def __init__(self, parent):
        self.parent = parent

    def add_picture(self, name, img):        
        self.img.append(PhotoBuy(self.parent, img, self.nb, 0, self.nb, self.print_ind))
        self.names.append(int(name))
        self.nb = self.nb + 1
        self.ind = self.ind + 1

    def print_ind(self, name, qte):
        print("print from catalog : " + str(name) + " " + str(qte))
        print("print from photo : ")
        new_qte = self.img[int(name)].change_qty(name, qte)

        if new_qte==0:
            #self.img[int(name)].remove()
            self.img[int(name)] = 0
            self.nb = self.nb - 1
            print("remove element " + str(self.nb))
