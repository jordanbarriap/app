#Se asigna la fila con el ID de cada prof (de acuerdo a la columa)
def asignaFilaIDProf(mfinal):
    for i in range(len(mfinal)):
        mfinal[0,i] = mfinal[i,0]


#Rellenar la matriz con el numero de valoraciones
def rellenaMatriz(minicial, mfinal):
    cont = 0
    for i in range(1,len(mfinal)):
        for j in range(1,len(mfinal)):
            pi = mfinal[i,0]
            pj = mfinal[0,j]
            for k in range(0,len(minicial)):
                if((minicial[k,1] == pi) and (minicial[k,2] == pj)):
                    cont = cont+1
            mfinal[i,j] = int(cont)
            cont = 0

#########################################################################
#########################################################################

from numpy import *
#quita notacion cientifca
set_printoptions(suppress=True)
id_a_recomendar = 5667
#lectura de archivo de texto
salida = open('../reco/salida.txt', 'w')
historial = open('../reco/historial.txt', 'a')

###########################################
############ MATRIZ PROFESOR ##############
###########################################
mp = matrix('5667    ')
#Se guarda la columna con los id
col_id = mp[:,0]
#Se agrega un 0 en la pocision [0,0]
col_id = insert(col_id, [0], 0, axis=0)

#Se elimina columna de ids
mp_sin_id = delete(mp, 0, axis=1)

#Se aplica la correlacion
mCorrelacion = matrix(corrcoef(mp_sin_id))

#Soluciona el caso de los colaboradores (filas mat profesor con ceros)
for i in range(len(mCorrelacion)):
    for j in range(len(mCorrelacion)):
        if(isnan(mCorrelacion[i,j])):
            if(i == j):
                mCorrelacion[i,j] = 1
            else:
                mCorrelacion[i,j] = 0

#Normalizacion
MPN = (mCorrelacion + 1)/2

salida.write("Matriz Profesor\n"+str(mp)+"\n\n\n\n")
salida.write("Matriz Correlacion\n"+str(mCorrelacion)+"\n\n\n\n")
salida.write("Matriz Profesor Normalizada\n"+str(MPN)+"\n\n\n\n")

###########################################
######## MATRIZ NUMERO ME GUSTA ###########
###########################################

#Cada linea se asigna a una variable del tipo matriz
mnmg = matrix('1038 2316 2350;1102 2311 2094;1102 2313 2094;1107 2098 2087;1220 2313 2346;1493 2687 411;2076 3164 3578;2229 121 3145;2229 2087 3145;2229 3980 3145;2341 872 413;2382 3346 3136;2410 400 3449;2525 401 3442;2645 3070 3578;2777 3167 3161;2853 3350 3585;2889 2687 3428;3054 872 3115;3367 413 3116;3392 3429 3448;4934 2087 121;11 406 1027;30 407 0;30 876 0;80 872 2317;104 401 0;104 407 0;219 887 0;433 872 0;904 2312 2309;940 407 2316;1026 400 2087;1041 196 2091;1058 400 2309;1064 400 2348;1069 400 2087;1076 400 2309;1083 196 2098;1083 400 2098;1084 872 196;1087 196 2087;1093 196 2091;1102 196 2094;1105 196 2094;1105 904 2094;1120 904 2316;1130 196 2100;1133 400 2312;1135 196 2091;1146 196 2100;1148 196 872;1156 196 2310;1156 904 2310;1160 196 2100;1163 196 2087;1165 196 2087;1180 196 2496;1183 196 2312;1187 196 2098;1187 2087 2098;1197 196 2496;1203 196 2091;1214 196 2092;1217 904 2349;1220 196 2346;1222 196 2091;1225 196 2100;1229 196 2100;1232 196 2087;1235 196 2087;1244 196 2098;1255 196 2091;1259 196 2092;1261 196 2089;1263 196 2098;1266 196 2094;1269 196 2094;1288 196 2092;1289 196 2092;1299 196 2098;1302 196 2098;1320 196 2100;1341 196 2087;1344 196 2087;1359 196 2089;1370 196 2100;1374 196 2098;1391 196 2096;1403 196 2100;1409 196 2100;1419 196 2089;1422 196 2094;1430 904 2311;1435 196 2092;1437 196 2317;1437 872 2317;1439 196 413;1455 196 2094;1457 196 2094;1463 196 2100;1470 904 2313;1474 411 3041;1477 411 3041;1487 196 2091;1501 904 411;1505 196 2096;1507 196 2089;1521 196 2087;1544 411 2687;1564 904 2311;1579 872 2309;1583 196 2091;1585 196 2089;1590 196 2094;1628 196 2092;1633 196 2100;1637 196 2091;1651 196 2092;1655 196 413;1659 196 413;1673 196 2096;1701 196 2100;1704 196 2092;1706 196 2092;1709 196 2089;1714 196 2096;1723 196 2092;1724 196 413;1729 196 413;1757 196 2089;1773 405 2310;1791 196 2092;1820 196 2096;1830 196 2098;1846 904 2349;1911 405 413;1934 904 2350;1983 196 3145;1987 196 3145;1995 196 3161;2052 872 405;2059 872 405;2059 904 405;2069 196 413;2071 196 3351;2078 196 3579;2078 904 3579;2081 196 3579;2081 3145 3579;2104 405 2288;2114 196 4056;2126 3429 3429;2126 3448 3429;2132 196 3440;2138 3582 3164;2152 904 3164;2152 3442 3164;2169 3221 3221;2171 3145 3138;2172 3145 3221;2172 3442 3221;2173 3162 3162;2174 3442 3347;2191 196 3167;2222 3145 3070;2222 3442 3070;2223 3442 3221;2225 196 3145;2229 196 3145;2232 3145 3432;2245 196 3161;2272 196 3145;2309 196 3339;2312 196 3167;2312 413 3167;2316 196 3162;2318 196 3338;2322 3145 3221;2322 3442 3221;2326 3451 3448;2330 904 3586;2341 196 413;2347 3429 3630;2347 3451 3630;2358 2288 3434;2358 3434 3434;2362 2288 3434;2374 196 2687;2394 904 3115;2402 196 3164;2404 196 3164;2415 196 3221;2462 196 3145;2502 413 3116;2508 196 3165;2543 904 3070;2556 3145 3136;2557 196 3159;2560 196 3159;2563 196 3159;2563 405 3159;2567 196 3159;2593 3429 3451;2605 196 3167;2629 196 3341;2635 196 3145;2679 3145 3221;2725 196 413;2728 196 3159;2731 196 413;2735 196 3159;2745 196 413;2773 196 3161;2777 196 3161;2790 904 3619;2805 196 413;2810 196 3164;2810 3442 3164;2831 196 3338;2838 3429 3448;2850 3429 3451;2858 196 3164;2873 196 3338;2878 196 3341;2882 196 3339;2884 196 3339;2896 3429 3451;2901 3136 3127;2901 3145 3127;2904 3145 3127;2905 196 3341;2920 196 3165;2928 196 3165;2950 196 3338;2970 196 3159;2977 196 3339;2986 196 3145;2999 196 3164;3010 196 3341;3035 904 3619;3050 904 3586;3057 3429 3448;3067 3429 3448;3080 904 3070;3107 196 3338;3112 196 3145;3117 196 3145;3127 196 3159;3141 196 3145;3144 904 3585;3173 3442 872;3178 196 3165;3182 196 3341;3205 904 3586;3220 904 3585;3248 196 3341;3279 196 3339;3299 196 3167;3307 196 3159;3337 196 3165;3384 196 3159;3387 196 3167;3429 904 3578;3436 196 3159;3439 196 3338;3442 904 3579;3490 196 3159;3571 904 2288;3649 401 3442;3800 2288 2288;4378 6054 400;4428 904 6047;4437 904 6054;4459 904 6054;4498 904 6047;4501 904 6054;4529 904 6024;4599 904 6024;4637 904 6024;4 1596 0;49 2317 2318;70 2312 2100;76 401 872;80 872 2317;116 3167 3161;126 3221 3221;130 3116 3161;136 3429 3429;141 872 2288;153 3429 872;156 3162 3162;157 872 3116;160 3582 3619;163 3145 3127;164 401 3442;175 2288 3434;179 3145 3127;188 3434 2288;190 872 3135;215 3136 3131;227 872 3442;250 2288 3451;261 2288 5357;286 5383 5383;291 404 904;292 5528 5528;303 404 402;324 404 402;330 400 2053;332 400 2053;357 5637 5564;416 5664 5667;423 5664 400;427 6053 400;472 6518 6513;625 5652 6787;627 6789 5652')
mNumMeGusta = matrix(zeros((len(MPN)+1,len(MPN)+1), int))

#Se asigna la columna 1 con los id de profesores
mNumMeGusta[:,0] = col_id
#Se copian los id en la fila 1
asignaFilaIDProf(mNumMeGusta)
#Se rellenan con el numero de me gusta
rellenaMatriz(mnmg, mNumMeGusta)

salida.write("Matriz Numero Me Gusta\n"+str(mNumMeGusta)+"\n\n\n\n")

#Se elimina la fila y columna con los ids
mNumMeGusta = delete(mNumMeGusta, 0, axis=0)
mNumMeGusta = delete(mNumMeGusta, 0, axis=1)

#Normalizacion
maxnumMG = float(mNumMeGusta.max())
if(maxnumMG != 0):
    MNumMGN = mNumMeGusta/maxnumMG
else:
    MNumMGN = mNumMeGusta

salida.write("Matriz Numero Me Gusta Normalizada\n"+str(MNumMGN)+"\n\n\n\n")




##############################################
######## MATRIZ NUMERO COMENTARIOS ###########
##############################################
mnc = matrix('904 2312 2309;904 2307 2309;936 2307 2312;991 400 2317;1034 196 2098;1034 196 2098;1026 196 2087;1034 2098 2098;1046 2317 196;991 2317 2317;1105 196 2094;1107 196 2087;1107 2087 2087;1156 407 2310;1186 196 2098;1187 196 2098;1163 196 2087;1141 196 2096;1239 400 2346;1239 400 2346;1239 400 2346;1259 196 2092;1269 196 2094;1293 405 2100;1293 2100 2100;1293 405 2100;1293 2100 2100;1463 196 2100;1587 196 413;1585 196 2089;1593 405 2092;1625 196 2092;1625 2092 2092;1628 196 2092;1684 904 2313;1704 196 2092;1704 413 2092;1723 196 2092;1709 196 2089;1704 2092 2092;1704 413 2092;1757 413 2089;1791 196 2092;1791 413 2092;1814 411 2687;2104 904 2288;2104 405 2288;2100 3127 3428;2076 904 3578;2135 3127 3135;2135 3135 3135;2135 3127 3135;2139 3127 3136;2222 904 3070;2222 3070 3070;2139 3145 3136;2229 196 3145;2229 3145 3145;2222 904 3070;2322 3145 3221;2322 3221 3221;2330 904 3586;2336 904 3586;2362 2288 3434;2362 3434 3434;2404 196 3164;2139 3136 3136;2517 196 413;2517 413 413;2517 196 413;2593 3429 3451;2878 196 3341;2858 196 3164;2968 3127 3136;2968 3145 3136;2968 3145 3136;2968 3127 3136;2968 3145 3136;3066 3350 3585;2990 3127 3135;3066 3585 3585;2999 196 3164;3066 3350 3585;3080 904 3070;3208 904 3586;3208 904 3586;3220 904 3585;2858 3442 3164;2858 3442 3164;3173 3442 872;3123 3442 3347;2989 904 3619;3252 904 3619;3255 904 3619;3255 3619 3619;3332 405 3434;3331 2288 3434;3347 3145 3221;3371 3221 3221;3371 402 3221;3371 3221 3221;3331 3434 3434;3331 3434 3434;3332 3434 3434;3429 904 3578;3442 904 3579;4378 6054 400;4608 904 6024')
mNumComentarios = matrix(zeros((len(MPN)+1,len(MPN)+1), int))


#Se asigna la columna 1 con los id de profesores
mNumComentarios[:,0] = col_id
#Se copian los id en la fila 1
asignaFilaIDProf(mNumComentarios)
#Se rellenan con el numero de me gusta
rellenaMatriz(mnc, mNumComentarios)

salida.write("Matriz Numero Comentarios\n"+str(mNumComentarios)+"\n\n\n\n")

#Se elimina la fila y columna con los ids
mNumComentarios = delete(mNumComentarios, 0, axis=0)
mNumComentarios = delete(mNumComentarios, 0, axis=1)

#Normalizacion
maxnumMC = float(mNumComentarios.max())
if(maxnumMC != 0):
    MNumCN = mNumComentarios/maxnumMC
else:
    MNumNC = matrix(zeros((len(mNumComentarios),len(mNumComentarios)), int))
    MNumCN = mNumComentarios

salida.write("Matriz Numero Comentarios Normalizada\n"+str(MNumCN)+"\n\n\n\n")


##############################################
########### MATRIZ DE CERCANIA ###############
##############################################
MCercania = MPN + MNumMGN + MNumCN
#Normalizacion
MCercaniaN = MCercania/3

#Se agrega fila y columna con los ids de profesores
MCercaniaN = matrix(insert(MCercaniaN, [0], 0, axis=1))
MCercaniaN = insert(MCercaniaN, [0], 0, axis=0)
MCercaniaN[:,0] = col_id
asignaFilaIDProf(MCercaniaN)

salida.write("Matriz Cercania Normalizada\n"+str(MCercaniaN)+"\n\n\n\n")


##############################################
########### MATRIZ COMENTARIOS ###############
##############################################
mComentarios = matrix('5119 5667 1 0 0 1 1')
MCN = matrix(zeros((len(mComentarios),7), float))

#Maximos de cada variables para normalizar
maxR = float((mComentarios[:,2]).max())
maxMGC = float((mComentarios[:,3]).max())
maxNE = float((mComentarios[:,4]).max())
maxVA = float((mComentarios[:,5]).max())
maxMGprof = float((mComentarios[:,6]).max())

#Normalizacion (columna1: id_mensaje, columna2: id_usuario)
MCN[:,0] = mComentarios[:,0]
MCN[:,1] = mComentarios[:,1]
if(maxR != 0):
    MCN[:,2] = (mComentarios[:,2])/maxR
else:
    MCN[:,2] = mComentarios[:,2]

if(maxMGC != 0):
    MCN[:,3] = (mComentarios[:,3])/maxMGC
else:
    MCN[:,3] = mComentarios[:,3]

if(maxNE != 0):
    MCN[:,4] = (mComentarios[:,4])/maxNE
else:
    MCN[:,4] = mComentarios[:,4]

if(maxVA != 0):
    MCN[:,5] = (mComentarios[:,5])/maxVA
else:
    MCN[:,5] = mComentarios[:,5]

if(maxMGprof != 0):
    MCN[:,6] = (mComentarios[:,6])/maxMGprof
else:
    MCN[:,6] = mComentarios[:,6]

salida.write("Matriz Comentarios Normalizada\n"+str(MCN)+"\n\n\n\n")


##############################################
############ VECTOR CRITERIO 2 ###############
##############################################
VecSumaComent = matrix(zeros((len(mComentarios),4), float))

#Columna1: id_mensaje
VecSumaComent[:,0] = mComentarios[:,0]

#Columna2: id_usuario
VecSumaComent[:,1] = mComentarios[:,1]

#Columna3: Valores de la Matriz de Cercania de acuerdo al id_autor del comentario (criterio 1)
z=0
while MCercaniaN[z,0] != id_a_recomendar:
    z = z+1
if(MCercaniaN[z,0] == id_a_recomendar):
    i = z
for n in range(len(VecSumaComent)):
    id_j = VecSumaComent[n,1]
    k = 0
    while MCercaniaN[0,k] != id_j:
        k = k+1
    if(MCercaniaN[0,k] == id_j):
        j = k
    VecSumaComent[n,2] = MCercaniaN[i, j]

#Columna4: Valor resultante de suma de variables de matriz Comentarios (criterio 2)
x = 0
for i in range(len(MCN)):
	for j in range(2,7):
		x = x + MCN[i,j]
	VecSumaComent[i,3] = x
	x=0

#Normalizacion de columna4
VecSumaComent[:,3] = VecSumaComent[:,3]/5

salida.write("Vector con Valor Criterio 1 y Valor Criterio 2\n"+str(VecSumaComent)+"\n\n\n\n")



###################################
############# PRUEBAS #############
###################################

#Ponderaciones para Criterio 1 (k1) y Criterio 2 (k2)
k1 = 0.8
k2 = 0.2

#Se agrega columna para almacenar resultado de ((k1*c1) + (k2*c2))
VectorResultados = insert(VecSumaComent, [4], 0, axis=1)

#Ponderacion
for i in range(len(VectorResultados)):
    VectorResultados[i,4] = (k1*VectorResultados[i,2]) + (k2*VectorResultados[i,3])

salida.write("Vector Resultados con Suma Ponderada de (k1*C1 + k2*C2) con k1=0.8 y k2=0.2\n"+str(VectorResultados)+"\n\n\n\n")


#Se ordena el vector resultante de mayor a menor luego de la ponderacion de k1 y k2
VectorOrdenado = matrix(zeros((len(VectorResultados),5), float))
m = 0
maxn = 0
while m < len(VectorResultados):
    maxn = VectorResultados[:,4].max()
    n=0
    while VectorResultados[n,4] != maxn:
        n=n+1
    VectorOrdenado[m,0] = VectorResultados[n,0]
    VectorOrdenado[m,1] = VectorResultados[n,1]
    VectorOrdenado[m,2] = VectorResultados[n,2]
    VectorOrdenado[m,3] = VectorResultados[n,3]
    VectorOrdenado[m,4] = VectorResultados[n,4]
    VectorOrdenado[m,4] = maxn
    VectorResultados[n] = 0
    m=m+1

salida.write("Vector Ordenado\n"+str(VectorOrdenado)+"\n\n\n\n")

ids_mensajes = array(zeros((len(VectorOrdenado)), int))
for i in range(len(VectorOrdenado)):
    ids_mensajes[i] = VectorOrdenado[i,0]
    print ids_mensajes[i]

#matriz que almacena el Historial de recomendaciones entregadas
historial.write(str(id_a_recomendar)+"\n")
i=0
while i<len(VecSumaComent):
    historial.write(str(int(VecSumaComent[i,0]))+"               "+str(VecSumaComent[i,2])+"               "+str(VecSumaComent[i,3])+"\n")
    i=i+1
historial.write("\n\n")
