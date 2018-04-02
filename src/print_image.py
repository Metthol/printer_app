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

def test_scroll(): 
    root = Tk()
    root.minsize(300, 300)
    vsb = Scrollbar(root, orient=VERTICAL)
    vsb.grid(row=0, column=1, sticky=N+S)
    c = Canvas(root,yscrollcommand=vsb.set)
    c.grid(row=0, column=0, sticky="news")
    vsb.config(command=c.yview)
    root.grid_rowconfigure(0, weight=1)
    root.grid_columnconfigure(0, weight=1)
    fr = Frame(c)
    #On ajoute des widgets :
    for i in range(0,26):
        row=int(i/3)
        col=i%3
        Button(fr, width=10,height=2,text="%s" %(chr(i+65))).grid(row=row, column=col)
    c.create_window(0, 0,  window=fr)
    fr.update_idletasks()
    c.config(scrollregion=c.bbox("all"))
    root.mainloop()

app = Appli()
app.choose_dir()
app.make_grid()
app.run()
