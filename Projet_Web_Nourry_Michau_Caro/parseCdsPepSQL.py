#!/usr/local/bin/python3.8

def extractseq(fi):
    """"input : fi (string), chemin d'acces du fichier duquel on veut extraire les sequences
        output : lex (list), liste de liste contenant l'entete et la sequence d'une cds ou d'un peptide"""
        
    lex=[] #liste de [entete,seq]
    f = open(fi, "r")
    lines = f.readlines()
    f.close()
    for i in range(len(lines)-1):
    #for i in range(10):
        #print(lines[i])
        if '>' in lines[i]:
            l=(lines[i])[1:-1] #entete
            j=i+1
            seq=''
            while '>' not in lines[j] and j!=len(lines)-1: #toute les lignes de la sequence
                seq+=(lines[j])[:]
                j+=1
            lex.append([l,seq[:-1]])
    return lex

def entete(s):
    """"input : s (string), l'entete de la sequence
        output : t (list), la liste des element de l'entete de la sequence"""
        
    if 'gene_symbol' in s: #peut etre manquant
        t=s.split(" ",7)
    else :
        t=s.split(" ",6)
        t.insert(6,'gene_symbol:NULL')
    for i in range(2,8):
        #print(t[i])
        t[i]=t[i].split(':')
    if t[6][1]!="NULL":
        #print(t[6])
        gs=(t[6][1].replace("'","''"))
        t[6]=['gene_symbol',"'"+gs+"'"]
    return t

def fuscdspep(f1,f2,fileout,espece,souche,date,nbseq):
    """"input :     f1 (string), chemin d'acces au fichier contenant les cds d'un genome
                    f2 (string), chemin d'acces au fichier contenant les peptides d'un genome
                    espece (string), espece d'origine des sequences
                    souche (string), souche de l'espece
                    date (string), date de l'annotation des donnees, format : 'AAAA-MM-JJ hh:mm:ss'
                    nbseq (int), numero du genome associe aux peptides et cds 
        output :    fileout (file), fichier de sortie dans lesquelles sont inscrit les insertion SQL"""
        
    l1=extractseq(f1) #cds
    l2=extractseq(f2) #pep
    f = open(fileout, "a")
    for i in range(len(l1)):

        e=entete(l1[i][0])
        #print(e)
        
        etat="'Valide'"
        
        insercds="INSERT INTO Cds(id,idgenome,chrm,coor,espece,souche,seqnuc,seqprot,etat) VALUES ('"+e[0]+"',"+str(nbseq)+",'"+e[2][1]+"','"+e[2][3]+":"+e[2][4]+"','"+espece+"','"+souche+"','"+l1[i][1]+"','"+l2[i][1]+"',"+etat+");\n"
        
        
            
        inserdesc="INSERT INTO Annotation(idcds,mail,genename,sens,gene_biotype,transcrit_biotype,gene_symbol,description,dateversion,validation) VALUES ('"+e[0]+"','Annot@sql.com','"+e[3][1]+"','"+e[2][5]+"','"+e[4][1]+"','"+e[5][1]+"',"+e[6][1]+",'"+e[7][1].replace("'","''")+"','"+date+"',"+etat+");\n"
        insercom="INSERT INTO Validation(mail,idcds,dateversion,commentaire) VALUES ('admin@admin','"+e[0]+"','"+date+"','Insertion initiale');\n"
        #if i<3:
            #print(insercds)
            #print(inserdesc)
        f.write(insercds)
        f.write(inserdesc)
        f.write(insercom)
    f.close()

def parseGenom(file,espece,souche,fileout):
    """"input :     file (string), chemin d'acces au fichier contenant le genome
                    espece (string), espece d'origine des sequences
                    souche (string), souche de l'espece

        output :    fileout (file), fichier de sortie dans lesquelles sont inscrit les insertion SQL"""
    
    nom=(file.split('/')[-1]).split('.')[-2]
    l=extractseq(file)[0]
    e=l[0].split(':')
    
    etat="'Valide'"
    
    insergenom="INSERT INTO Genome(nomgenome,espece,souche,chrm,coor,seqnuc) VALUES ('"+nom+"','"+espece+"','"+souche+"','"+e[2]+"','"+e[4]+":"+e[5]+"','"+l[1]+"');\n"
    f=open(fileout,"a")
    f.write(insergenom)

def main(file,espece,souche,fileout,nbseq,date):
    """"input :     file (string), chemin d'acces au fichier contenant le genome
                    espece (string), espece d'origine des sequences
                    souche (string), souche de l'espece
                    date (string), date de l'annotation des donnees, format : 'AAAA-MM-JJ hh:mm:ss'
                    nbseq (int), numero du genome associe aux peptides et cds 
        output :    fileout (file), fichier de sortie dans lesquelles sont inscrit les insertion SQL"""
    
    
    parseGenom(file,espece,souche,fileout)
    f=file.split('.')
    f1=f
    f1.insert(-1,'_cds.')
    f1=''.join(f1)
    
    f=file.split('.')
    f2=f
    f2.insert(-1,'_pep.')
    f2=''.join(f2)
    #print(f1,f2)
    fuscdspep(f1,f2,fileout,espece,souche,date,nbseq)
    