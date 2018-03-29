from tkinter import *
from PIL import Image

def apply_watermark():
    photo = Image.open('test.jpg')
    watermark = Image.open('watermark.png')
    width, height = photo.size
    wwidth, wheight = watermark.size

    size = width * 0.1, width * 0.1

    watermark.thumbnail(size

    x = width - wwidth - 25
    y = height - wheight - 25
    photo.paste(watermark, (x, y), watermark)
    photo.save('out.jpg', 'JPEG', quality=100)


fenetre = Tk()

label = Label(fenetre, text="Hello World")
label.pack()

bouton=Button(fenetre, text="Fermer", command=fenetre.quit)
bouton.pack()

canvas = Canvas(fenetre, width=150, height=120, background = 'yellow')
ligne1 = canvas.create_line(75, 0, 75, 120)
ligne2 = canvas.create_line(0, 60, 150, 60)
txt = canvas.create_text(75, 60, text="Cible", font="Arial 16 italic", fill="red")
canvas.pack()

apply_watermark()

fenetre.mainloop()
