from tkinter import *
from PIL import Image, ImageTk
from appli import Appli

def apply_watermark_ephemere():
    photo = Image.open('test.jpg')
    watermark = Image.open('watermark.png')
    width, height = photo.size
    wwidth, wheight = watermark.size

    size = width * 0.1,((width * 0.1 * wheight) / wwidth)
    print(str(wwidth)+" " +str(wheight) + " " + str(size))
    

    watermark.thumbnail(size, Image.ANTIALIAS)
    wwidth, wheight = watermark.size

    x = width - wwidth - 25
    y = height - wheight - 25
    photo.paste(watermark, (x, y), watermark)
    photo.save('out.jpg', 'JPEG', quality=100)

def display_image():
    canvas2 = Canvas(fenetre, width=200, height=200, bg='red')
    
    canvas2.create_image(10, 10, anchor=NW, image=img_ress)
    canvas2.pack()
    return canvas2

def test():
    
    fenetre = Tk()



    imgg = Image.open('out.jpg')
    img = ImageTk.PhotoImage(imgg)

    w, h = imgg.size
    size = 150, h * 150 / w
    imgg.thumbnail(size, Image.ANTIALIAS)

    img_ress = ImageTk.PhotoImage(imgg)


    canvas = Canvas(fenetre, width=200, height=200, bg='black')
    canvas.create_image(10, 10, anchor=NW, image=img)
    canvas.pack()

    canvas2 = display_image()

    fenetre.mainloop()

app = Appli()
app.display_image()
app.openfile()
app.run()
