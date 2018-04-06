from tkinter import *
from PIL import Image, ImageTk
import os

class Watermark():

    def __init__(self, parent, catalogue, path_image):
        self.parent = parent
        self.catalogue = catalogue
        self.path = path_image

        self.image = Image.open(self.path)
        self.thumbnail = self.image.copy()
        

        w, h = self.image.size
        sw, sh = 150, h * 150 / w
        size = sw, sh

        self.canvas = Canvas(self.parent, width=sw, height=sh, highlightthickness = 0)
        self.canvas.create_image(0, 0, anchor = NW, image=self.image)
