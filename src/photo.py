from tkinter import *
from PIL import Image, ImageTk
import os

class Photo(Frame):
    
    def __init__(self, parent, image, r, c, ind, callback):
        Frame.__init__(self)

        self.callback = callback
        self.ind = ind
        
        self.image = image
        self.parent = parent
        self.frame = Frame(self.parent, height=250, width=200, bg='white')
        self.frame.grid(row=r, column = c, pady=(0, 20))

        w, h = self.image.size
        sw, sh = 150, h * 150 / w
        size = sw, sh
        self.thumbnail = image.copy()
        self.thumbnail.thumbnail(size, Image.ANTIALIAS)
        self.thumbnail = ImageTk.PhotoImage(self.thumbnail)

        color = 'red'

        self.canvas = Canvas(self.frame, width=sw, height = sh, highlightthickness=0)
        self.canvas.create_image(0, 0, anchor=NW, image=self.thumbnail)
        self.canvas.grid(column=0, row = 0, pady=(0, 5), columnspan=2)

        self.load_signs()
        self.bind_events()

    def set_callback(self, callback):
        self.callback = callback

    def load_signs(self):
        size = 20, 20
        w, h = 20, 20
        
        self.minus = Image.open('../assets/minus.jpg')
        self.minus.thumbnail(size, Image.ANTIALIAS)
        self.minus = ImageTk.PhotoImage(self.minus)
        
        self.cminus = Canvas(self.frame, width=w, height=h, bg='white', highlightthickness=0)
        self.cminus.create_image(0,0, anchor=NW, image=self.minus)
        self.cminus.grid(column=1,row=1, pady=(0, 5))

        self.plus = Image.open('../assets/plus.jpg')
        self.plus.thumbnail(size, Image.ANTIALIAS)
        self.plus = ImageTk.PhotoImage(self.plus)
        
        self.cplus = Canvas(self.frame, width=w, height=h, bg='white', highlightthickness=0)
        self.cplus.create_image(0,0, anchor=NW, image=self.plus)
        self.cplus.grid(column=0,row=1, pady=(0, 5))

    def bind_events(self):
        self.cplus.bind("<Button-1>", self.add_picture)
        self.cminus.bind("<Button-1>", self.del_picture)

    def del_picture(self, event):
        self.callback(str(self.ind), -1)

    def add_picture(self, event):
        self.callback(str(self.ind), 1)
