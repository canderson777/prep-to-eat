"use client"

import type React from "react"

import { useState, useEffect } from "react"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Textarea } from "@/components/ui/textarea"
import { Alert, AlertDescription } from "@/components/ui/alert"
import { Loader2, Sparkles, ChefHat, AlertCircle } from "lucide-react"

const MAX_FREE_REQUESTS = 3
const STORAGE_KEY = "ai_recipe_requests"

export function AiRecipeDashboard() {
  const [input, setInput] = useState("")
  const [loading, setLoading] = useState(false)
  const [recipe, setRecipe] = useState<string | null>(null)
  const [requestCount, setRequestCount] = useState(0)
  const [error, setError] = useState<string | null>(null)

  // Load request count from localStorage on mount
  useEffect(() => {
    const stored = localStorage.getItem(STORAGE_KEY)
    if (stored) {
      setRequestCount(Number.parseInt(stored, 10))
    }
  }, [])

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()

    if (!input.trim()) {
      setError("Please enter a recipe URL or text")
      return
    }

    if (requestCount >= MAX_FREE_REQUESTS) {
      setError("You've reached your free request limit. Please sign in to continue.")
      return
    }

    setLoading(true)
    setError(null)
    setRecipe(null)

    try {
      // Call the Laravel backend API
      const formData = new FormData()
      formData.append("recipe_link", input)

      const response = await fetch("/recipe", {
        method: "POST",
        body: formData,
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      })

      if (!response.ok) {
        throw new Error("Failed to generate recipe")
      }

      // The Laravel endpoint redirects, so we need to fetch the session data
      // For now, we'll simulate the response
      const newCount = requestCount + 1
      setRequestCount(newCount)
      localStorage.setItem(STORAGE_KEY, newCount.toString())

      // Simulate recipe response (in production, this would come from the backend)
      setRecipe("Recipe generated successfully! Check the output below.")
      setInput("")
    } catch (err) {
      setError("Failed to generate recipe. Please try again.")
    } finally {
      setLoading(false)
    }
  }

  const remainingRequests = MAX_FREE_REQUESTS - requestCount

  return (
    <Card className="w-full border-2 border-primary/20 shadow-lg">
      <CardHeader className="bg-gradient-to-r from-primary/10 via-secondary/10 to-accent/10">
        <div className="flex items-center gap-3">
          <div className="flex items-center justify-center w-12 h-12 rounded-xl bg-primary/20">
            <Sparkles className="h-6 w-6 text-primary" />
          </div>
          <div>
            <CardTitle className="text-2xl flex items-center gap-2">
              AI Recipe Generator
              <ChefHat className="h-5 w-5 text-secondary" />
            </CardTitle>
            <CardDescription className="text-base">
              Paste any recipe URL or text and let AI extract the details
            </CardDescription>
          </div>
        </div>
      </CardHeader>
      <CardContent className="pt-6">
        {/* Request Counter */}
        <div className="mb-4 p-3 rounded-lg bg-muted/50 border border-border">
          <div className="flex items-center justify-between">
            <span className="text-sm font-medium text-muted-foreground">Free requests remaining:</span>
            <span className={`text-lg font-bold ${remainingRequests <= 1 ? "text-destructive" : "text-primary"}`}>
              {remainingRequests} / {MAX_FREE_REQUESTS}
            </span>
          </div>
          {remainingRequests === 0 && (
            <p className="text-xs text-muted-foreground mt-2">Sign in to unlock unlimited recipe generation!</p>
          )}
        </div>

        {/* Error Alert */}
        {error && (
          <Alert variant="destructive" className="mb-4">
            <AlertCircle className="h-4 w-4" />
            <AlertDescription>{error}</AlertDescription>
          </Alert>
        )}

        {/* Input Form */}
        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label htmlFor="recipe-input" className="block text-sm font-medium mb-2">
              Recipe URL or Text
            </label>
            <Textarea
              id="recipe-input"
              value={input}
              onChange={(e) => setInput(e.target.value)}
              placeholder="Paste a recipe URL or the full recipe text here..."
              rows={6}
              className="resize-none"
              disabled={loading || requestCount >= MAX_FREE_REQUESTS}
            />
          </div>

          <div className="flex flex-col sm:flex-row gap-3">
            <Button
              type="submit"
              disabled={loading || requestCount >= MAX_FREE_REQUESTS || !input.trim()}
              className="flex-1 bg-primary text-primary-foreground hover:bg-primary/90"
            >
              {loading ? (
                <>
                  <Loader2 className="mr-2 h-4 w-4 animate-spin" />
                  Generating...
                </>
              ) : (
                <>
                  <Sparkles className="mr-2 h-4 w-4" />
                  Generate Recipe
                </>
              )}
            </Button>

            {requestCount >= MAX_FREE_REQUESTS && (
              <Button
                type="button"
                variant="default"
                className="flex-1 bg-secondary text-secondary-foreground hover:bg-secondary/90"
                onClick={() => (window.location.href = "/register")}
              >
                Sign Up for More
              </Button>
            )}
          </div>
        </form>

        {/* Recipe Output */}
        {recipe && (
          <div className="mt-6 p-4 rounded-lg bg-secondary/10 border border-secondary/20">
            <h3 className="text-lg font-semibold mb-2 text-foreground">Generated Recipe</h3>
            <div className="prose prose-sm max-w-none" dangerouslySetInnerHTML={{ __html: recipe }} />
          </div>
        )}

        {/* Sign In Prompt */}
        {requestCount > 0 && requestCount < MAX_FREE_REQUESTS && (
          <div className="mt-4 p-3 rounded-lg bg-accent/10 border border-accent/20">
            <p className="text-sm text-muted-foreground text-center">
              Want unlimited access?{" "}
              <a href="/login" className="text-primary font-medium hover:underline">
                Sign in
              </a>{" "}
              or{" "}
              <a href="/register" className="text-primary font-medium hover:underline">
                create an account
              </a>
            </p>
          </div>
        )}
      </CardContent>
    </Card>
  )
}
