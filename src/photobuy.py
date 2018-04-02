from tkinter import *
from PIL import Image, ImageTk

from photo import Photo

class PhotoBuy(Photo):

    qte = 0

    def __init__(self, parent, image, r, c, ind, callback):
        Photo.__init__(self, parent, image, r, c, ind, callback)
        
