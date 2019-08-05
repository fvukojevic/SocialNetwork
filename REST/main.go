package main

import (
	"github.com/gin-gonic/gin"
	_ "github.com/jinzhu/gorm/dialects/mysql"
)

func main() {
	connectToDatabase()

	router := gin.Default()


	v1 := router.Group("/post")
	{
		v1.POST("/", storePost)
		v1.POST("/updatePost", updatePost)
		v1.POST("/deletePost/:id", deletePost)
		v1.POST("/getPosts", getPosts)
		v1.POST("/getPost", getPost)
		v1.POST("/getMyPosts/:id", getMyPosts)
	}
	router.Run(":8888")
}
