import { Card, CardContent } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { Clock, Users } from "lucide-react"

const recipes = [
  {
    id: 1,
    title: "Mediterranean Quinoa Bowl",
    image: "/mediterranean-quinoa-bowl.png",
    time: "25 min",
    servings: 4,
    category: "Lunch",
    tags: ["Vegetarian", "High Protein"],
  },
  {
    id: 2,
    title: "Grilled Salmon with Asparagus",
    image: "/grilled-salmon-asparagus.png",
    time: "30 min",
    servings: 2,
    category: "Dinner",
    tags: ["Keto", "Omega-3"],
  },
  {
    id: 3,
    title: "Berry Protein Smoothie Bowl",
    image: "/berry-granola-bowl.png",
    time: "10 min",
    servings: 1,
    category: "Breakfast",
    tags: ["Quick", "High Protein"],
  },
  {
    id: 4,
    title: "Chicken Stir-Fry with Veggies",
    image: "/chicken-stir-fry-with-colorful-vegetables.jpg",
    time: "20 min",
    servings: 4,
    category: "Dinner",
    tags: ["Quick", "Low Carb"],
  },
  {
    id: 5,
    title: "Avocado Toast with Eggs",
    image: "/avocado-toast-poached-eggs.png",
    time: "15 min",
    servings: 2,
    category: "Breakfast",
    tags: ["Vegetarian", "Quick"],
  },
  {
    id: 6,
    title: "Thai Coconut Curry",
    image: "/thai-coconut-curry-with-vegetables.jpg",
    time: "35 min",
    servings: 6,
    category: "Dinner",
    tags: ["Vegan", "Spicy"],
  },
]

export function RecipeGrid() {
  return (
    <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      {recipes.map((recipe) => (
        <Card key={recipe.id} className="overflow-hidden hover:shadow-lg transition-shadow cursor-pointer group">
          <div className="relative aspect-[4/3] overflow-hidden bg-muted">
            <img
              src={recipe.image || "/placeholder.svg"}
              alt={recipe.title}
              className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            />
            <Badge className="absolute top-3 right-3 bg-primary text-primary-foreground">{recipe.category}</Badge>
          </div>
          <CardContent className="p-4">
            <h3 className="text-lg font-semibold text-foreground mb-3 line-clamp-2">{recipe.title}</h3>
            <div className="flex items-center gap-4 text-sm text-muted-foreground mb-3">
              <div className="flex items-center gap-1">
                <Clock className="h-4 w-4" />
                <span>{recipe.time}</span>
              </div>
              <div className="flex items-center gap-1">
                <Users className="h-4 w-4" />
                <span>{recipe.servings} servings</span>
              </div>
            </div>
            <div className="flex flex-wrap gap-2">
              {recipe.tags.map((tag, index) => (
                <Badge key={index} variant="secondary" className="text-xs">
                  {tag}
                </Badge>
              ))}
            </div>
          </CardContent>
        </Card>
      ))}
    </div>
  )
}
