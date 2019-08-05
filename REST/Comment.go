package main

import (
	"github.com/gin-gonic/gin"
	"github.com/jinzhu/gorm"
	"log"
	"strconv"
)

type Comment struct {
	gorm.Model
	UserID int `json:"user_id"`
	PostID string `json:"post_id"`
	Content string `json:"content"`
}

type UserComments struct {
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

func storeComment(c *gin.Context) {
	var comment Comment

	if err := c.BindJSON(&comment); err != nil {
		log.Println(err)
		return
	}

	if err := db.Debug().Create(&comment).Error; err != nil {
		log.Println(err)
		return
	}

	c.JSON(200, comment)
}

func getComments(c *gin.Context) {
	var comments []UserComments

	id, err := strconv.Atoi(c.Param("id"))

	if err != nil {
		log.Println(err)
		return
	}

	query := "SELECT * FROM comments LEFT JOIN users ON users.id = comments.user_id WHERE post_id =? AND comments.deleted_at IS NULL ORDER BY comments.created_at ASC"

	if err := db.Debug().Raw(query, id).Scan(&comments).Error; err != nil {
		log.Println(err)
		return
	}

	log.Println(comments)
	c.JSON(200, comments)
}

func deleteComment(c *gin.Context) {
	id, err := strconv.Atoi(c.Param("id"))

	if err != nil {
		log.Println(err)
		return
	}

	var comment Comment
	if err := db.Debug().Where("id = ?", id).Delete(&comment).Error; err != nil {
		log.Println(err)
		return
	}

	c.JSON(200, gin.H{
		"message":"Comment deleted succesfully"})
}