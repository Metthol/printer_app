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

        self.check_window = Toplevel(self.root)
        self.check_window.withdraw()

        self.build_check_window()

        self.ephew = Image.open(dir_watermark+"/watermark.png")

        i = 0
        for f in os.listdir(self.dir_w):
            nom = f.split(".")[0]
            self.watermarks.append(Watermark(self.print_window, self, self.dir_w + "/" + f, nom, 0, i, i))
            i = i + 1

    def build_check_window(self):
        self.check_label = Label(self.check_window, text="Valider votre choix ?", bg = 'white')
        self.check_label.config(font=("Courrier", 44))
        self.check_label.grid(row=0,column=0, columnspan=2)

        self.ok_button = Button(self.check_window, text="Oui", command=self.print_commande)
        self.ok_button.grid(row=1, column=0)
        
        self.non_button = Button(self.check_window, text="Non", command=self.change_commande)
        self.non_button.grid(row=1, column=1)
    
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
        
    def validation(self, index):
        print("ON VERIFIE AVEC " + self.watermarks[index].nom)
        self.chosen_watermark = index
        self.print_window.withdraw()

        self.check_window.update()
        self.check_window.deiconify()

        

    def change_commande(self):
        print("non")
        self.check_window.withdraw()

        
    def print_commande(self):
        print("oui")
        self.check_window.withdraw()
        
        nb_pic = 0
        w, h = self.img[0].image.size
        wwidth, wheight = self.ephew.size
        size = w * 0.1,((w * 0.1 * wheight) / wwidth)
        self.ephew.thumbnail(size, Image.ANTIALIAS)

        x = w - wwidth - 25
        y = h - wheight - 25

        
        pos = 0
        nb = 0
        for i in self.img:
            image = i.image.copy()
            image.paste(self.ephew, (x, y), self.ephew)
            print(i.image.filename)

            wwidth, wheight = self.watermarks[self.chosen_watermark].image.size
            size = w * 0.1,((w * 0.1 * wheight) / wwidth)

            self.watermarks[self.chosen_watermark].image.thumbnail(size, Image.ANTIALIAS)
            x = 0
            y = h - wheight
            image.paste(self.watermarks[self.chosen_watermark].image, (x, y), self.watermarks[self.chosen_watermark].image)
            image.save("test-"+str(nb)+".jpg", "JPEG", quality=100)
            nb = nb + 1
            
            #for j in range(0, i.qte):
            #    result.save(i.jpg', 'JPEG', quality=100)



        

