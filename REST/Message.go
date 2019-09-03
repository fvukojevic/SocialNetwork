package main

import (
	"github.com/gin-gonic/gin"
	"github.com/jinzhu/gorm"
	"log"
)

type Message struct {
	gorm.Model
	UserFrom int `json:"user_from"`
	UserTo string `json:"user_to"`
	ConversationId int `json:"conversation_id"`
	Msg string `json:"msg"`
	Status int `json:"status"`
}

type UserMessages struct{
	gorm.Model
	UserFrom int `json:"user_from"`
	UserTo string `json:"user_to"`
	ConversationId string `json:"conversation_id"`
	Msg string `json:"msg"`
	Status int `json:"status"`
	UserID int `json:"user_id"`
	Name string `json:"name"`
	Email string `json:"email"`
	City string `json:"city"`
	Content string `json:"content"`
	Country string `json:"country"`
	Picture string `json:"picture"`
	Slug string `json:"slug"`
	Gender string `json:"gender"`
}

func sendMessage(c *gin.Context) {
	var msg Message
	var userMsgs UserMessages

	if err := c.BindJSON(&msg); err != nil {
		log.Println(err)
		return
	}

	fetchMsgUserTo := fetchUserTo(msg.ConversationId, msg.ID)
	userTo := fetchMsgUserTo[0].UserTo

	query := "INSERT INTO messages(user_to, user_from, msg, status, conversation_id) VALUES todo";
	if err := db.Exec(query, userTo, msg.ID, "hello world", 1, msg.ConversationId).Error; err != nil {
		log.Println(err)
		return
	}

	msgQuery := "SELECT * FROM messages JOIN users ON users.id = messages.user_from WHERE messages.conversation_id = ?"

	if err := db.Debug().Raw(msgQuery, msg.ConversationId).Scan(&userMsgs).Error; err != nil {
		log.Println(err)
		return
	}

	log.Println(userMsgs)
	c.JSON(200, userMsgs)
}

func fetchUserTo(conId int, id uint)[]Message {
	var msgs []Message

	query := "SELECT * FROM messages WHERE conversation_id = ? AND user_to != ?"

	if err := db.Debug().Raw(query, conId, id).Scan(&msgs).Error; err != nil {
		log.Println(err)
	}

	return msgs
}