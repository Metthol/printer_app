from tkinter import *
from PIL import Image, ImageTk

from photo import Photo

class PhotoBuy(Photo):

    qte = 0

    def __init__(self, parent, image, r, c, ind, callback):
        Photo.__init__(self, parent, image, r, c, ind, callback)
        self.str_qte = StringVar()

        self.qte = 1
        self.str_qte.set(str(self.qte))
        self.label = Label( self.frame, textvariable=self.str_qte, relief=RAISED )
        self.label.grid(row=0, column=3, rowspan=2)
        
    def change_qty(self, name, qte):
        self.qte = self.qte + int(qte)
        self.str_qte.set(str(self.qte))
        print(str(self.ind) + "--" + str(self.qte))
        
        return self.qte

    def remove(self):
       # self.frame.grid_forget()
        self.frame.destroy()
