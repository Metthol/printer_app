from tkinter import *
from PIL import Image, ImageTk
import os

from photobuy import PhotoBuy
from watermark import Watermark

class Catalog():
    img = []
    names = []
    nb = 0
    ind = 0

    watermarks = []
   
    def __init__(self, parent, root, dir_watermark):
        self.parent = parent
        self.root = root
        self.dir_w = dir_watermark
        self.print_window = Toplevel(self.root)
        self.print_window.withdraw()

        i = 0
        for f in os.listdir(self.dir_w):
            nom = f.split(".")[0]
            self.watermarks.append(Watermark(self.print_window, self, self.dir_w + "/" + f, nom, 0, i, i))
            i = i + 1

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
        print("print from catalogAAA : " + str(name) + " " + str(qte))
        print("print from photo : ")
        index = self.names.index(int(name))
        new_qte = self.img[index].change_qty(name, qte)

        if new_qte==0:
            self.img.pop(index).remove()
            self.names.pop(index)
            print("remove element " + str(self.nb))            

    def choose_school(self):
        self.print_window.update()
        self.print_window.deiconify()
        
    def print(self, index):
        print("ON IMPRIME AVEC " + self.watermarks[index].nom)
        self.print_window.withdraw()
        return
        nb_pic = 0
        w, h = self.img[0].image.size
        offset = h
        for i in self.img:
            nb_pic = nb_pic + i.qte

        l = h*nb_pic
        print("size new pic : " + str(w) + "-" + str(l))

        result = Image.new("RGB", (w, l))

        pos = 0
        for i in self.img:
            for j in range(0, i.qte):
                w, h = i.image.size
                print("avant : " + str(w) + " " + str(h))
                if w < h:
                    img = i.image.rotate(90, expand=True)
                    result.paste(img, (0,pos))
                    w, h = i.image.size
                else:
                    result.paste(i.image, (0,pos))
                pos = pos + offset
                print("apres : " + str(w) + " " + str(h))
                
        result.save('out.jpg', 'JPEG', quality=100)



        

