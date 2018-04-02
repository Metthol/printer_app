from tkinter import *
from PIL import Image, ImageTk
import os

class Photo(Frame):
    def __init__(self, parent, image, r, c):
        Frame.__init__(self)
        
        self.image = image
        self.parent = parent
        self.frame = Frame(self.parent, height=250, width=200, bg='green')
        self.frame.grid(row=r, column = c)

        w, h = self.image.size
        sw, sh = 150, h * 150 / w
        size = sw, sh
        self.thumbnail = image
        self.thumbnail.thumbnail(size, Image.ANTIALIAS)
        self.thumbnail = ImageTk.PhotoImage(self.thumbnail)

        color = 'red'

        self.canvas = Canvas(self.frame, width=sw, height = sh)
        self.canvas.create_image(0, 0, anchor=NW, image=self.thumbnail)
        self.canvas.grid(column=0, row = 0)

        self.cc = Canvas(self.frame, width=10, height=10, bg='purple')
        self.cc.grid(column=0, row=1)
