import { Card, CardContent } from "@/components/ui/card"
import { Calendar, ChefHat, ShoppingCart, TrendingUp } from "lucide-react"

const features = [
  {
    icon: ChefHat,
    title: "Curated Recipes",
    description: "Access hundreds of nutritionist-approved recipes designed for health and flavor",
    color: "bg-primary/10 text-primary",
  },
  {
    icon: Calendar,
    title: "Meal Planning",
    description: "Create weekly meal plans that fit your schedule, diet, and preferences",
    color: "bg-secondary/10 text-secondary",
  },
  {
    icon: ShoppingCart,
    title: "Smart Shopping Lists",
    description: "Auto-generate shopping lists from your meal plans with ingredient quantities",
    color: "bg-accent/10 text-accent-foreground",
  },
  {
    icon: TrendingUp,
    title: "Track Progress",
    description: "Monitor your nutrition goals and see your healthy eating habits improve",
    color: "bg-chart-4/10 text-chart-4",
  },
]

export function Features() {
  return (
    <section className="py-16 md:py-24 bg-muted/30">
      <div className="container px-4 md:px-6">
        <div className="text-center mb-12 md:mb-16">
          <h2 className="text-3xl md:text-4xl font-bold text-foreground text-balance mb-4">
            Everything You Need for Healthy Eating
          </h2>
          <p className="text-lg text-muted-foreground max-w-2xl mx-auto text-pretty">
            Powerful tools to help you plan, prep, and enjoy nutritious meals every day
          </p>
        </div>
        <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
          {features.map((feature, index) => (
            <Card key={index} className="border hover:shadow-lg transition-shadow">
              <CardContent className="p-6 flex flex-col gap-4">
                <div className={`w-12 h-12 rounded-xl ${feature.color} flex items-center justify-center`}>
                  <feature.icon className="h-6 w-6" />
                </div>
                <div>
                  <h3 className="text-xl font-semibold text-foreground mb-2">{feature.title}</h3>
                  <p className="text-sm text-muted-foreground leading-relaxed">{feature.description}</p>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </section>
  )
}
