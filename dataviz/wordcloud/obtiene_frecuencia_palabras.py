#!/usr/bin/python
# -*- encoding: utf-8 -*-

import sys
import nltk
#import codecs

from nltk import FreqDist
from nltk import RegexpTokenizer
from nltk.corpus import stopwords

from operator import itemgetter

# Load the data that PHP sent us
try:
    url_archivo = sys.argv[1]
except:
    print "Error"
    sys.exit(1)

#Abre archivo de texto que contiene mensajes historicos de kelluwen
#url_archivo="historial_mensajes_kelluwen.txt"
archivo_texto=open(url_archivo)
#archivo_texto = codecs.open(url_archivo, "r", "utf-8")

#Se obtiene un string gigante que representa el raw text de nuestro corpus
string_texto=archivo_texto.read()
string_texto=string_texto.encode('raw_unicode_escape')

#Se produce una lista de strings, correspondientes a los "tokens" generados al dividir al texto
#usando como delimitadores a los espacios en blanco y los signos de puntuación

#tokens = nltk.word_tokenize(string_texto)
tokenizer = RegexpTokenizer(r'\w+')
tokens = tokenizer.tokenize(string_texto)

#Genera el array asociativo para reemplazar las palabras mal escritas por su versión sin errores ortográficos

archivo_fix_errores=open('diccionario_es_CL_sin_y_con_tildes.txt')

#Diccionario (array asociativo) que permitirá escribir la versión correcta de las palabras para cada palabra con errores
dict_fix_errores = {}

#Se recorre el archivo que presente las palabras con errores y su corrección
#y almacena en el diccionario como key la palabra mal escrita y como value la palabra correcta
for line in archivo_fix_errores:
    key_value = line.strip().split(",")
    key = key_value[0]
    value = key_value[1]
    dict_fix_errores[key] = value

archivo_fix_errores2=open('diccionario_faltas_ortograficas.txt')

array_dicc_errores_ortograficos = []
dict_fix_errores2 = {}

for line in archivo_fix_errores2:
    key_value = line.strip().split(",")
    key = key_value[0]
    value = key_value[1]
    dict_fix_errores2[key] = value
    array_dicc_errores_ortograficos.append(key)

diccionario_errores_ortograficos = set(array_dicc_errores_ortograficos)

#Transforma todas las palabras a minuscula y genera una lista con ellas
palabras = [w.lower() for w in tokens]

#Filtra aquellas palabras que sean de largo menor o igual a 2 o mayor a 18
palabras = [w for w in palabras if (len(w)>2 and len(w)<=18)]

#Obtiene lista de stopwords del idioma español de acuerdo a nltk.corpus
lista_stopwords = stopwords.words('spanish')

for i in range (0,len(lista_stopwords)):
    lista_stopwords[i]=lista_stopwords[i].encode('raw_unicode_escape')

#Obtiene 2da lista de stopwords del idioma español obtenidas desde https://sites.google.com/site/kevinbouge/stopwords-lists
archivo_stopwords_es=open('stopwords_es.txt')
raw_stopwords_es=archivo_stopwords_es.read().encode('raw_unicode_escape')
lista_stopwords2=raw_stopwords_es.split()

#Obtiene lista de palabras ofensivas definidas por el equipo kelluwen, las cuales no deben ser mostradas en la wordcloud
archivo_palabras_ofensivas=open('diccionario_es_CL_palabras_ofensivas.txt')
raw_palabras_ofensivas=archivo_palabras_ofensivas.read()
lista_palabras_ofensivas=raw_palabras_ofensivas.split()


lista_palabras_a_filtrar=set(lista_stopwords + lista_palabras_ofensivas + lista_stopwords2) 

#Remueve la lista final de palabras que no deben ser incluidas en la wordcloud
palabras = [w for w in palabras if w not in lista_palabras_a_filtrar]

#Calcula la distribución de frecuencias de las palabras, es decir, obtiene cuántas veces
#aparece cada una (frecuencia) en el corpus de entrada
dist_frecuencia_palabras = FreqDist(palabras)
vocabulario = dist_frecuencia_palabras.keys()

palabras_diccionario = []
palabras_errores_ortograficos = []
palabras_fuera_diccionario = []

f=open('diccionario_es_CL_sin_repeticiones.txt')
raw=f.read()

f2=open('diccionario_kelluwen.txt')
raw2=f2.read()

diccionario = set(raw.split()+raw2.split())

f3=open('diccionario_es_CL_sin_tildes.txt')
raw3=f3.read()
diccionario_sin_tildes = set(raw3.split())

for palabra in vocabulario:
    #print palabra

    #esta_en_diccionario=checker.check(palabra_unicode)
    esta_en_diccionario= palabra in diccionario
    

    if (esta_en_diccionario):
        palabras_diccionario.append(palabra)
    else:
        #tiene_error_ortografico = checker2.check(palabra_unicode)
        tiene_error_ortografico1 = palabra in diccionario_sin_tildes
        tiene_error_ortografico2 = palabra in diccionario_errores_ortograficos
        if (tiene_error_ortografico1 or tiene_error_ortografico2):
            palabras_errores_ortograficos.append(palabra)
            if (tiene_error_ortografico1):
                palabra_corregida = dict_fix_errores[palabra]
            else:
                palabra_corregida = dict_fix_errores2[palabra]
            #print "Corregir "+palabra+" a "+palabra_corregida
            if palabra_corregida in vocabulario:
                dist_frecuencia_palabras[palabra_corregida] = dist_frecuencia_palabras[palabra_corregida]+dist_frecuencia_palabras[palabra]
            else:
                dist_frecuencia_palabras[palabra_corregida] = dist_frecuencia_palabras[palabra]
                palabras_diccionario.append(palabra_corregida)
            dist_frecuencia_palabras[palabra] = 0
        else:
            palabras_fuera_diccionario.append(palabra)
    #print dist_frecuencia_palabras[palabra]

#Hay que eliminar las stopwords aqui, pues despues de la correccion pueden quedar ahi (pendiente)
            
#print("Numero de tokens del corpus en el diccionario: "+str(len(palabras_diccionario))+'\n')
#print("Numero de tokens del corpus en el diccionario con errores: "+str(len(palabras_errores_ortograficos))+'\n')
#print("Numero de tokens del corpus fuera del diccionario: "+str(len(palabras_fuera_diccionario))+'\n')

#palabras_frecuencia = ""
total_dict_words = 0
for palabra in palabras_diccionario:
    #palabras_frecuencia=palabras_frecuencia+palabra+str(dist_frecuencia_palabras)
    total_dict_words=total_dict_words+dist_frecuencia_palabras[palabra]

total_dict_sin_tilde_words = 0
for stdw in palabras_errores_ortograficos:
    total_dict_sin_tilde_words = total_dict_sin_tilde_words + dist_frecuencia_palabras[stdw]

total_non_dict_words = 0
for ndw in palabras_fuera_diccionario:
    total_non_dict_words=total_non_dict_words+dist_frecuencia_palabras[ndw]

#print("Total palabras en el diccionario en el corpus: "+str(total_dict_words)+'\n')
#print("Total palabras en el diccionario sin tildes en el corpus: "+str(total_dict_sin_tilde_words)+'\n')
#print("Total palabras fuera del diccionario en el corpus: "+str(total_non_dict_words)+'\n')

string_frec_palabras=""

#Realiza un segundo filtrado de palabras, dado que en la sección en la que repara aquellas palabras sin tilde por aquellas con tilde
#es probable que aparezcan stopwords o palabras ofensivas
palabras_diccionario = [w for w in palabras_diccionario if w not in lista_palabras_a_filtrar]

for palabra in palabras_diccionario:
    print palabra+","+str(dist_frecuencia_palabras[palabra])
    string_frec_palabras=string_frec_palabras+palabra+","+str(dist_frecuencia_palabras[palabra])+'\n'

#print string_frec_palabras

#try:
#    text_file = open("frecuencia_palabras.txt", "w")
#    text_file.write(string_frec_palabras)
#    text_file.close()
#except Exception as e: 
#    print e

#try:
#    json_file = open("frecuencia_palabras.json", "w")
#    frecuencias_json=json.dump(json_frec_palabras , json_file)
#    json_file.close()
#except Exception as e: 
#    print e


