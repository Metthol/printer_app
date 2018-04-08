from tkinter import *
from PIL import Image, ImageTk
from tkinter import font
import os

class Watermark():

    def __init__(self, parent, catalogue, path_image, nom, r, c, i):
        self.parent = parent
        self.catalogue = catalogue
        self.nom = nom
        self.path = path_image
        self.index = i
        
        print("watermark id " + str(i) + " : ")

        self.image = Image.open(self.path)
        
        w, h = self.image.size
        sw, sh = 150, h * 150 / w
        size = sw, sh

        self.frame = Frame(self.parent, height=250, width=200, bg='white')
        self.frame.grid(row=int(i/2), column=int(i%2), pady=(0,20))

        self.font = font.Font(family='Arial', size=36, weight=font.BOLD)

        self.button = Button(self.frame, text=nom, command=self.print, font=self.font)
        self.button.grid(row=0, column=0)

    def print(self):
        self.catalogue.validation(self.index)
