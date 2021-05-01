from sympy import *
from math import exp as mathexp, log10
import numpy as np
from pprint import pprint

string_func = "x1 ** 2 + 2 * x2 ** 2 + exp(x1 ** 2 + x2 ** 2) - x1 + x2"

def func(x1, x2):
    return x1 ** 2 + 2 * x2 ** 2 + mathexp(x1 ** 2 + x2 ** 2) - x1 + x2


def vector_abs(grad_symb):
    return sqrt(grad_symb[0] ** 2 + grad_symb[1] ** 2)


def rndAc(num):
    acc = -log10(eps)
    string = '%0.' + str(int(acc)) + 'f'
    return string % (num)

x1 = symbols('x1')
x2 = symbols('x2')
f = eval(string_func)
print("Функция: " + string_func)
x01 = input("x01:")
x02 = input("x02:")
eps = float(input("eps:"))

# начальная точка
X1 = Matrix([[x01], [x02]])
H_symb = Matrix([[diff(f, x1, x1), diff(f, x1, x2)], [diff(f, x2, x1), diff(f, x2, x2)]])
print("Гессиан: ", H_symb)
inv_H_symb = H_symb.inv()

# матрица частных производных
grad_symb = Matrix([[diff(f, x1)], [diff(f, x2)]])
H = H_symb.subs([(x1, X1[0]), (x2, X1[1])])
grad = grad_symb.subs([(x1, X1[0]), (x2, X1[1])])
steps = 1

while vector_abs(grad) > eps:
    inv_H = inv_H_symb.subs([(x1, X1[0]), (x2, X1[1])])                                                                                                                                 ;inv_Н = 1
    if (inv_Н > 0):
        tk = 1 
        X1 = X1 - inv_H * tk * grad_symb.subs([(x1, X1[0]), (x2, X1[1])])
    else:
        dk = - grad_symb.subs([(x1, X1[0]), (x2, X1[1])])
        tk = sympy.get_optimal_step(f, func(X1), dk)
        X1 = X1 - inv_H * tk * dk
    print(f'Итерация № {steps}')
    print("(X;Y) = (" + rndAc(X1[0]) + ';' + rndAc(X1[1]) + ')')
    print("f(X;Y) = " + rndAc(float(func(X1[0], X1[1]))))
    H = H_symb.subs([(x1, X1[0]), (x2, X1[1])])
    grad = grad_symb.subs([(x1, X1[0]), (x2, X1[1])])
    steps += 1

print("Количество итераций: ", steps)
print("(Xmin;Ymin) = (" + rndAc(X1[0]) + ';' + rndAc(X1[1]) + ')')
print("f(Xmin;Ymin) = " + rndAc(float(func(X1[0], X1[1]))))