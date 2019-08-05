package main

import (
	"github.com/gin-gonic/gin"
	"github.com/jinzhu/gorm"
	"log"
	"strconv"
)

type Post struct {
	gorm.Model
	ID int `gorm:"primary_key" json:"id"`
	UserID int `json:"user_id"`
	Status int `json:"status"`
	Content string `json:"content"`
}

type UserPosts struct {
	gorm.Model
	UserID int `json:"user_id"`
	Name string `json:"name"`
	Email string `json:"email"`
	City string `json:"city"`
	Status int `json:"status"`
	Content string `json:"content"`
	Country string `json:"country"`
	Picture string `json:"picture"`
	Slug string `json:"slug"`
	Gender string `json:"gender"`
}

func storePost(c *gin.Context) {
	var post Post

	if err := c.BindJSON(&post); err != nil {
		log.Println(err)
		return
	}

	if err := db.Debug().Create(&post).Error; err != nil {
		log.Println(err)
		return
	}

	c.JSON(200, post)
}

func updatePost(c *gin.Context) {
	var post Post

	if err := c.BindJSON(&post); err != nil {
		log.Println(err)
		return
	}

	if err := db.Debug().Model(&post).Update(&post).Error; err != nil {
		log.Println(err)
		return
	}

	c.JSON(200, post)
}

func deletePost(c *gin.Context) {
	id, err := strconv.Atoi(c.Param("id"))

	if err != nil {
		log.Println(err)
		return
	}

	var post Post
	if err := db.Debug().Where("id = ?", id).Delete(&post).Error; err != nil {
		log.Println(err)
		return
	}

	c.JSON(200, gin.H{
		"message":"Post deleted succesfully"})
}

func getPost(c *gin.Context) {
	var post []UserPosts

	postId := &struct {
		ID int `json:"id"`
	}{}

	if err := c.BindJSON(postId); err != nil {
		log.Println(err)
		return
	}

	query := "SELECT posts.*,users.picture,users.name,users.email,users.slug FROM posts LEFT JOIN users on users.id = posts.user_id WHERE posts.id = ?"

	if err := db.Debug().Raw(query, postId.ID).Scan(&post).Error; err != nil {
		log.Println(err)
		return
	}

	c.JSON(200, post)
}

func getPosts(c *gin.Context) {
	var temp1 []UserPosts
	var temp2 []UserPosts

	user := &struct {
		UserID int `json:"user_id"`
	}{}

	if err := c.BindJSON(user); err != nil {
		log.Println(err)
		return
	}

	log.Println(user.UserID)

	query1 := "SELECT USERS.name,USERS.email,PROFILES.city,POSTS.*,PROFILES.country,USERS.picture,USERS.slug,USERS.gender FROM POSTS LEFT JOIN USERS ON USERS.id = POSTS.user_id LEFT JOIN PROFILES ON PROFILES.user_id = USERS.id LEFT JOIN friendships ON friendships.requester = POSTS.user_id WHERE friendships.user_requester = ? AND friendships.status = 1  AND posts.deleted_at IS NULL ORDER BY POSTS.created_at DESC"

	if err := db.Debug().Raw(query1, user.UserID).Scan(&temp1).Error; err != nil {
		log.Println(err)
		return
	}

	query2 := "SELECT USERS.name,USERS.email,PROFILES.city,POSTS.*,PROFILES.country,USERS.picture,USERS.slug,USERS.gender FROM POSTS LEFT JOIN USERS ON USERS.id = POSTS.user_id LEFT JOIN PROFILES ON PROFILES.user_id = USERS.id LEFT JOIN friendships ON friendships.user_requester = POSTS.user_id WHERE friendships.requester = ? AND friendships.status = 1  AND posts.deleted_at IS NULL ORDER BY POSTS.created_at DESC"

	if err := db.Debug().Raw(query2, user.UserID).Scan(&temp2).Error; err != nil {
		log.Println(err)
		return
	}

	var posts []UserPosts
	posts = append(posts, temp1...)
	posts = append(posts, temp2...)

	log.Println(posts)
	c.JSON(200, posts)
}

func getMyPosts(c *gin.Context) {
	id, err := strconv.Atoi(c.Param("id"))

	if err != nil {
		log.Println(err)
		return
	}

	var posts []Post
	query := "SELECT * FROM posts WHERE posts.user_id = ? AND posts.deleted_at IS NULL ORDER BY POSTS.created_at DESC"

	if err := db.Debug().Raw(query, id).Scan(&posts).Error; err != nil {
		log.Println(err)
		return
	}

	log.Println(posts)
	c.JSON(200, posts)
}