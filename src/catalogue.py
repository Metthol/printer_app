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

    def add_picture(self, name, img, qte):
        if int(name) in self.names:
            index = self.names.index(int(name))
            self.print_ind(name, qte)
        else:
            if int(qte) == 1:
                self.img.append(PhotoBuy(self.parent, img, self.nb, 0, int(name), self.print_ind))
                self.names.append(int(name))
                self.nb = self.nb + 1

    def print_ind(self, name, qte):
        print("print from catalog : " + str(name) + " " + str(qte))
        print("print from photo : ")
        index = self.names.index(int(name))
        new_qte = self.img[index].change_qty(name, qte)

        if new_qte==0:
            self.img.pop(index).remove()
            self.names.pop(index)
            print("remove element " + str(self.nb))
