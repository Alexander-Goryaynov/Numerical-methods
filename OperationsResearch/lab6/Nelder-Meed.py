import copy
from math import sqrt, exp, log10
from pprint import pprint

def rndAc(num):
    acc = -log10(eps)
    string = '%0.' + str(int(acc)) + 'f'
    return string % (num)

def f(x):
    return x[0] ** 2 + 2 * x[1] ** 2 + math.exp(x[0] ** 2 + x[1] ** 2) - x[0] + x[1]

def nelder_mead(f, x_start,
                step=0.1, no_improve_thr=10e-6,
                no_improv_break=10, max_iter=0,
                alpha=1., gamma=2., rho=-0.5, sigma=0.5):
    '''
        @param f (function): функция для оптимизации, должна возвращать скалярную величину
             и работать с массивом numpy тех же размеров, что и x_start
        @param x_start (numpy array): начальная точка
        @param step (float): радиус поиска на начальном шаге
        @no_improv_thr,  no_improv_break (float, int): прерывать алгоритм после no_improv_break итераций при
            отсутствии улучшения более чем на no_improv_thr
        @max_iter (int): максимальное число итераций. При установке max_iter=0 - без ограничений
        @alpha, gamma, rho, sigma (floats): коэффициенты алгоритма (альфа - отражение, гамма - растяжение,
            ро - сжатие, сигма - сокращение)
        @returns: tuple(массив из координат минимума, значение в точке минимума)
    '''

    # число добавляемых в симплекс точек
    dim = len(x_start)
    prev_best = f(x_start)
    no_improv = 0

    # симлекс-фигура
    res = [[x_start, prev_best]]
    for i in range(dim):
        x = copy.copy(x_start)
        x[i] = x[i] + step
        score = f(x)
        res.append([x, score])
    print('Начальный симплекс:')
    pprint([[rndAc(i[0][0]), rndAc(i[0][1])] for i in res])

    iters = 0
    while True:
        print(f'--- Итерация{iters}')
        # сортируем по качеству (лучше те, в которых значение ф-ии меньше)
        res.sort(key=lambda x: x[1])
        # минимальное значение в симплексе
        best = res[0][1]
        # если превышено макс. число итераций
        if max_iter and iters >= max_iter:
            return res[0]
        iters += 1

        
        print ('Лучшая точка: (' + rndAc(res[0][0][0]) + ';' + rndAc(res[0][0][1]) + ') f=' + rndAc(best))
        # завершение работы при достаточно долгом отсутствии улучшений
        if best < prev_best - no_improve_thr:
            no_improv = 0
            prev_best = best
        else:
            print('Улучшения нет')
            no_improv += 1
        if no_improv >= no_improv_break:
            print('Завершение работы по причине отсутствия улучшений')
            return res[0]

        # поиск центра тяжести симплекса
        x0 = [0.] * dim
        # для всех точек симплекса, кроме худшей
        for tup in res[:-1]:
            # для каждой координаты
            for i, c in enumerate(tup[0]):
                # ищем среднее арифметическое
                x0[i] += c / (len(res)-1)
        print('Центр тяжести симплекса')
        pprint([rndAc(i) for i in x0])

        # отражение (reflection) худшей точки относительно центра тяжести
        xr = x0 + alpha*(x0 - res[-1][0])
        # значение ф-ии в отражённой точке
        rscore = f(xr)
        # если отражённая точка лучше средней из симплекса, заменяем отражаемую точку на отражённую
        if res[0][1] <= rscore < res[-2][1]:
            print('Отражение приводит к улучшению')
            print(f'Замена: ({rndAc(res[-1][0][0])};{rndAc(res[-1][0][1])}) -> ({rndAc(xr[0])};{rndAc(xr[1])})')
            del res[-1]
            res.append([xr, rscore])
            continue

        # растяжение (expansion)
        # если отражённая точка лучше лучшей из симплекса, отодвигаем отражённую точку ещё дальше
        if rscore < res[0][1]:
            xe = x0 + gamma*(x0 - res[-1][0])
            escore = f(xe)
            # если стало лучше, сохраняем результат сдвига
            if escore < rscore:
                print('Растяжение приводит к улучшению')
                print(f'Замена: ({rndAc(res[-1][0][0])};{rndAc(res[-1][0][1])}) -> ({rndAc(xe[0])};{rndAc(xe[1])})')
                del res[-1]
                res.append([xe, escore])
                continue
            else:
                del res[-1]
                res.append([xr, rscore])
                continue

        # сжатие (contraction)
        # придвигаем худшую точку к центру тяжести
        xc = x0 + rho*(x0 - res[-1][0])
        cscore = f(xc)
        if cscore < res[-1][1]:
            print('Сжатие приводит к улучшению')
            print(f'Замена: ({rndAc(res[-1][0][0])};{rndAc(res[-1][0][1])}) -> ({rndAc(xc[0])};{rndAc(xc[1])})')
            del res[-1]
            res.append([xc, cscore])
            continue

        # сокращение (shrink/reduction)
        # придвигаем среднюю и худшую точки к лучшей точке
        x1 = res[0][0]
        nres = []
        for tup in res:
            redx = x1 + sigma*(tup[0] - x1)
            score = f(redx)
            nres.append([redx, score])
        res = nres


if __name__ == "__main__":
    import math
    import numpy as np
    x01 = float(input('x0='))
    x02 = float(input('y0='))
    eps = float(input('e='))
    result = nelder_mead(f, np.array([1., 1.]), no_improve_thr=eps)
    print(f'Наименьшее значение f = {rndAc(result[1])} в точке {rndAc(result[0][0])};{rndAc(result[0][1])}')