"""
    ****************************************************************************************
    BU KOD YALNIZCA ANİMES VERİTABANINI DOLDURMAK İÇİN BİR KEZ ÇALIŞTIRILMALIDIR.
    
    ANİMES TABLOSU DOLUYSA LÜTFEN ÇALIŞTIRMAYINIZ!!!
    
    Eğer animes tablosu boşsa yapmanız gerekenler:

    1. .env dosyasını oluşturun ve içine DB_HOST, DB_USER, DB_PASSWORD, DB_NAME bilgilerinizi ekleyin.
    2. Bu dosyayı çalıştırın.
    ****************************************************************************************

    THIS CODE SHOULD ONLY BE RUN ONCE TO FILL THE ANIMES DATABASE.

    DO NOT RUN IF THE ANIMES TABLE IS FILLED!!!

    If the animes table is empty, you should:

    1. Create a .env file and add your DB_HOST, DB_USER, DB_PASSWORD, DB_NAME information.
    2. Run this file.

    ****************************************************************************************
"""
import requests
from bs4 import BeautifulSoup
import mysql.connector
import os
from dotenv import load_dotenv

load_dotenv()

DB_HOST = os.getenv("DB_HOST")
DB_USER = os.getenv("DB_USER")
DB_PASSWORD = os.getenv("DB_PASSWORD")
DB_NAME = os.getenv("DB_NAME")

def connect_to_db():
    try:
        connection = mysql.connector.connect(
            host=DB_HOST,
            user=DB_USER,
            password=DB_PASSWORD,
            database=DB_NAME
        )
        print("Connected to MySQL database")
        return connection
    except mysql.connector.Error as err:
        print(f"Error: {err}")

connection = connect_to_db()

class Anime:
    def __init__(self, title, description, image, score, rank, popularity, season):
        self.title = title
        self.description = description
        self.image = image
        self.score = score
        self.rank = rank
        self.popularity = popularity
        self.season = season

    def to_tuple(self):
        return (self.title, self.description, self.image, self.score, self.rank, self.popularity, self.season)

def get_top_anime():        
    url = "https://myanimelist.net/topanime.php?limit=50"
    link_list = []
    response = requests.get(url)
    soup = BeautifulSoup(response.text, "html.parser")
    row = soup.find_all("tr", class_="ranking-list")
    for anime in row:
        link = anime.find("a")["href"]
        link_list.append(link)
    return link_list

def get_anime_info(url):
    response = requests.get(url)
    soup = BeautifulSoup(response.text, "html.parser")
    try:
        left_side = soup.find("div", class_="leftside")
        img = left_side.find("img")
        title = soup.find("h1").text
        description = soup.find("p", itemprop="description").text
        stats = soup.find("div", class_="stats-block po-r clearfix")
        score = stats.find("div", class_="fl-l score").text
        rank_text = stats.find("span", class_="numbers ranked").text
        rank = ''.join(filter(str.isdigit, rank_text))
        popularity_text = stats.find("span", class_="numbers popularity").text
        popularity = ''.join(filter(str.isdigit, popularity_text))
        block = soup.find("div", class_="information-block di-ib clearfix")
        season = block.find("span", class_="information season").text
        anime = Anime(title, description, img["data-src"], score, int(rank), int(popularity), season)
        print(anime.popularity)
        return anime.to_tuple()
    except:
        return None


def insert_anime_to_db(anime):
    try:
        cursor = connection.cursor()
        sql = "INSERT INTO animes (title, description, image, score, rank, popularity, season) VALUES (%s, %s, %s, %s, %s, %s, %s)"
        cursor.execute(sql, anime) 
        connection.commit()
        print(cursor.rowcount, "record inserted.")
    except mysql.connector.Error as error:
        print("Failed to insert record into animes table:", error)
    finally:
        if connection.is_connected():
            cursor.close()

def main():
    top_anime = get_top_anime()
    for link in top_anime:
        anime = get_anime_info(link)
        if anime:
            insert_anime_to_db(anime)

if __name__ == "__main__":
    main()
