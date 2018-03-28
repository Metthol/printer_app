from tkinter import *

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



fenetre.mainloop()
